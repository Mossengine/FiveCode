<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\FiveCode;

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
            'call' => function($fiveCode, $arrayData) { return self::call($fiveCode, $arrayData); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|false|mixed|null
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
            && $fiveCode->isFunctionAllowed($stringFunctionName)
        ) {
            if (
                is_callable(
                    $callable = $fiveCode->functionGet($stringFunctionName, null)
                )
            ) {
                $mixedResult = call_user_func_array(
                    $callable,
                    [
                        $fiveCode,
                        $arrayArguments
                    ]
                );
            } else if (
                function_exists($stringFunctionName)
            ) {
                // Call the function to get the results
                $mixedResult = call_user_func_array(
                    $stringFunctionName,
                    $arrayArguments
                );
            } else {
                $mixedResult = null;
            }
        }

        return $fiveCode->result($mixedResult);
    }

}