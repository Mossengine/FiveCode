<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\ParserNotAllowedException;
use Mossengine\FiveCode\Exceptions\ParserNotFoundException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\Helper;

/**
 * Class Executes
 * @package Mossengine\FiveCode\Parsers
 */
class Executes extends ParsersAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'define' => function($fiveCode, $arrayData) { return self::define($fiveCode, $arrayData); },
            'call' => function($fiveCode, $arrayData) { return self::call($fiveCode, $arrayData); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|mixed|null
     */
    public static function define(FiveCode $fiveCode, array $arrayArguments = []) {
        if (
            count($arrayArguments) > 0
            && !empty($stringFunctionName = Helper::Arrays()::get($arrayArguments, 0, null))
        ) {
            $fiveCode->functionSet(
                $stringFunctionName,
                Helper::Arrays()::get($arrayArguments, 1, null)
            );
        }

        return $fiveCode->result();
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|mixed|null
     * @throws ParserNotAllowedException
     * @throws ParserNotFoundException
     */
    public static function call(FiveCode $fiveCode, array $arrayArguments = []) {
        $mixedResult = $fiveCode->result();

        // Get the arguments
        $arrayArguments = array_map(
            function ($arrayArgument) use ($fiveCode) {
                return $fiveCode->instructions($arrayArgument);
            },
            $arrayArguments
        );

        if (
            count($arrayArguments) > 0
            && !empty($stringFunctionName = array_shift($arrayArguments))
        ) {
            $mixedCallableOrDefined = $fiveCode->functionGet($stringFunctionName, null);

            if (
                is_array($mixedCallableOrDefined)
            ) {
                $mixedResult = FiveCode::make([
                    'variables' => [
                        'include' => [
                            '_arguments' => $arrayArguments
                        ]
                    ],
                    'debug' => true
                ])
                    ->evaluate($mixedCallableOrDefined)
                    ->return();
            } else if (
                $fiveCode->isFunctionAllowed($stringFunctionName)
            ) {
                if (is_callable($mixedCallableOrDefined)) {
                    $mixedResult = call_user_func_array(
                        $mixedCallableOrDefined,
                        [
                            $fiveCode,
                            $arrayArguments
                        ]
                    );
                } else if (function_exists($stringFunctionName)) {
                    // Call the function to get the results
                    $mixedResult = call_user_func_array(
                        $stringFunctionName,
                        $arrayArguments
                    );
                } else {
                    $mixedResult = null;
                }
            } else {
                $mixedResult = null;
            }
        }

        return $fiveCode->result($mixedResult);
    }

}