<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;

/**
 * Class Executes
 * @package Mossengine\FiveCode\Parsers
 */
class Executes extends ModuleAbstract {

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
     * @throws InstructionException
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
            $callable = $fiveCode->functionGet($stringFunctionName, null);
            $mixedResult = call_user_func_array(
                (
                    is_callable($callable)
                        ? $callable
                        : (
                            function_exists($stringFunctionName)
                                ? $stringFunctionName
                                : function() { return null; }
                        )
                ),
                $arrayArguments
            );
        }

        return $fiveCode->result($mixedResult);
    }

}