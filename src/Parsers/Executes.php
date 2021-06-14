<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

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
            'execute' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'executes' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayExecutes
     * @return false|mixed|null
     */
    public static function parse(FiveCode $fiveCode, array $arrayExecutes = []) {
        $mixedResult = null;
        foreach ($arrayExecutes as $arrayExecute) {
            $stringFunctionName = ___::arrayFirstKey($arrayExecute);
            $mixedFunctionData = ___::arrayGet($arrayExecute, $stringFunctionName, null);

            $callable = $fiveCode->functionGet($stringFunctionName, null);

            $mixedResult = call_user_func_array(
                (
                    is_callable($callable)
                        ? $callable
                        : (
                            $fiveCode->isFunctionAllowed($stringFunctionName)
                            && function_exists($stringFunctionName)
                                ? $stringFunctionName
                                : function() { return null; }
                        )
                ),
                array_map(
                    function (array $arrayArgument) use ($fiveCode) {
                        $stringArgumentKey = ___::arrayFirstKey($arrayArgument);
                        $mixedArgumentValue = ___::arrayGet($arrayArgument, $stringArgumentKey, null);
                        switch ($stringArgumentKey) {
                            case 'variable':
                                return (
                                    $fiveCode->isVariableAllowed($mixedArgumentValue, 'get')
                                        ? $fiveCode->variableGet($mixedArgumentValue, null)
                                        : null
                                );
                            default:
                                return $mixedArgumentValue;
                        }
                    },
                    ___::arrayGet($mixedFunctionData, 'arguments', [])
                )
            );

            if (!empty($mixedResult)) {
                foreach (___::arrayGet($mixedFunctionData, 'returns', []) as $arrayReturn) {
                    $stringReturnKey = ___::arrayFirstKey($arrayReturn);
                    $mixedReturnValue = ___::arrayGet($arrayReturn, $stringReturnKey, null);
                    switch ($stringReturnKey) {
                        case 'variable':
                            if ($fiveCode->isVariableAllowed($mixedReturnValue, 'set')) {
                                $fiveCode->variableSet($mixedReturnValue, $mixedResult);
                            }
                            break;
                    }
                }
            }

        }

        $fiveCode->variableSet('return', $mixedResult);
        return $mixedResult;
    }

}