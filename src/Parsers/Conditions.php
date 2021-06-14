<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\FunctionException;
use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Conditions
 * @package Mossengine\FiveCode\Parsers
 */
class Conditions extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'condition' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'conditions' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayConditions
     * @return bool|mixed|FiveCode|null
     * @throws FunctionException
     * @throws InstructionException
     */
    public static function parse(FiveCode $fiveCode, array $arrayConditions = []) {
        $fiveCode->loopUp('conditions');
        $mixedResult = null;

        if ($fiveCode->isLoopUnder('conditions', 10)) {
            foreach ($arrayConditions as $arrayCondition) {
                $stringConditionType = ___::arrayFirstKey($arrayCondition);
                $arrayConditionData = ___::arrayGet($arrayCondition, $stringConditionType, null);
                $arrayStatements = ___::arrayGet($arrayConditionData, 'statements', null);

                foreach ($arrayStatements as $arrayStatement) {
                    $stringStatementType = ___::arrayFirstKey($arrayStatement);
                    $arrayStatementData = ___::arrayGet($arrayStatement, $stringStatementType, null);

                    if (in_array($stringStatementType, ['condition', 'conditions'])) {
                        $mixedResult = self::parse($fiveCode, $arrayStatementData);
                    } else {
                        $arrayArguments = array_map(
                            function (array $arrayArgument) {
                                $stringArgumentKey = ___::arrayFirstKey($arrayArgument);
                                $mixedArgumentValue = ___::arrayGet($arrayArgument, $stringArgumentKey, null);
                                switch ($stringArgumentKey) {
                                    case 'variable':
                                        return (
                                            $this->isVariableAllowed($mixedArgumentValue, 'get')
                                                ? $this->variableGet($mixedArgumentValue, null)
                                                : null
                                        );
                                    default:
                                        return $mixedArgumentValue;
                                }
                            },
                            ___::arrayGet($arrayStatementData, 'arguments', [])
                        );

                        // support more than one argument, middle argument is the operator ( type )
                        switch (count($arrayArguments)) {
                            case 1:
                                $mixedLeft = $arrayArguments[0];
                                $mixedRight = null;
                                break;
                            case 2:
                                $mixedLeft = $arrayArguments[0];
                                $mixedRight = $arrayArguments[1];
                                break;
                            case 3:
                                $mixedLeft = $arrayArguments[0];
                                $stringStatementType = $arrayArguments[1];
                                $mixedRight = $arrayArguments[2];
                                break;
                            default:
                                $mixedLeft = null;
                                $mixedRight = null;
                        }

                        switch ($stringStatementType) {
                            case 'lt':
                            case '<':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft < 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft < $mixedRight)
                                        )
                                );
                                break;
                            case 'lte':
                            case '<=':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft <= 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft <= $mixedRight)
                                        )
                                );
                                break;
                            case 'eq':
                            case '==':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft == 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft == $mixedRight)
                                        )
                                );
                                break;
                            case '===':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft === 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft === $mixedRight)
                                        )
                                );
                                break;
                            case 'neq':
                            case '!=':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft != 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft != $mixedRight)
                                        )
                                );
                                break;
                            case 'gt':
                            case '>':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft > 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft > $mixedRight)
                                        )
                                );
                                break;
                            case 'gte':
                            case '>=':
                                $mixedResult = (
                                    is_numeric($mixedLeft)
                                    && is_null($mixedRight)
                                        ? ($mixedLeft >= 0)
                                        : (
                                            is_numeric($mixedLeft)
                                            && is_numeric($mixedRight)
                                            && ($mixedLeft >= $mixedRight)
                                        )
                                );
                                break;
                            default:
                                $mixedResult = false;
                        }

                        if (
                            (
                                $mixedResult
                                && is_array(
                                    $arrayInstructions = ___::arrayGet($arrayStatementData, 'true', null)
                                )
                            )
                            || (
                                !$mixedResult
                                && is_array(
                                    $arrayInstructions = ___::arrayGet($arrayStatementData, 'false', null)
                                )
                            )
                        ) {
                            $mixedResult = $fiveCode->parse($arrayInstructions);
                        }
                    }

                    if (
                        (
                            $mixedResult
                            && 'some' === $stringConditionType
                        )
                        || (
                            !$mixedResult
                            && 'every' === $stringConditionType
                        )
                    ) {
                        break;
                    }
                }

                if (
                    (
                        $mixedResult
                        && is_array(
                            $arrayInstructions = ___::arrayGet($arrayConditionData, 'true', null)
                        )
                    )
                    || (
                        !$mixedResult
                        && is_array(
                            $arrayInstructions = ___::arrayGet($arrayConditionData, 'false', null)
                        )
                    )
                ) {
                    $mixedResult = $fiveCode->parse($arrayInstructions);
                }
            }
        }
        $fiveCode->loopDown('conditions');
        $fiveCode->variableSet('return', $mixedResult);
        return $mixedResult;
    }

}