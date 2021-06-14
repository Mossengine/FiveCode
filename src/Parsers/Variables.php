<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Variables
 * @package Mossengine\FiveCode\Parsers
 */
class Variables extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'variable' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'variables' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayVariables
     * @return array|\ArrayAccess|bool|mixed|null
     * @throws InstructionException
     */
    public static function parse(FiveCode $fiveCode, array $arrayVariables = []) {
        $mixedResult = null;

        foreach ($arrayVariables as $arrayVariable) {
            $stringVariableType = ___::arrayFirstKey($arrayVariable);
            $mixedVariableData = ___::arrayGet($arrayVariable, $stringVariableType, []);
            $mixedVariableKey = ___::arrayGet($mixedVariableData, 'key', ___::arrayFirstKey($mixedVariableData));
            $mixedVariableValueOrDefault = ___::arrayGet($mixedVariableData, 'value', ___::arrayFirstValue($mixedVariableData));

            switch ($stringVariableType) {
                case 'all':
                    $mixedResult = (
                        empty($fiveCode->variablesAllowed())
                        || (
                            (
                                ['*'] === array_keys($fiveCode->variablesAllowed())
                                && $fiveCode->isVariableAllowed('*', 'get')
                            )
                                ? $fiveCode->variables()
                                : []
                        )
                    );
                    break;
                case 'get':
                    $mixedResult = (
                        $fiveCode->isVariableAllowed($mixedVariableKey, 'get')
                            ? $fiveCode->variableGet($mixedVariableKey, $mixedVariableValueOrDefault)
                            : null
                    );
                    break;
                case 'set':
                    if ($fiveCode->isVariableAllowed($mixedVariableKey, 'set')) {
                        $fiveCode->variableSet($mixedVariableKey, $mixedVariableValueOrDefault);
                    }
                    $mixedResult = $fiveCode->variableGet('return', $mixedResult);
                    break;
                case 'forget':
                    if ($fiveCode->isVariableAllowed($mixedVariableKey, 'forget')) {
                        $fiveCode->variableForget($mixedVariableKey);
                    }
                    $mixedResult = $fiveCode->variableGet('return', $mixedResult);
                    break;
                default:
                    throw new InstructionException('Invalid variable type : ' . $stringVariableType);
            }
        }

        $fiveCode->variableSet('return', $mixedResult);
        return $mixedResult;
    }

}