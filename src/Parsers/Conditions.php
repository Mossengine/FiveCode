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

                // any | all
                $stringConditionType = ___::arrayFirstKey($arrayCondition);

                // array of statements ( things that resolve to true )
                $arrayStatements = ___::arrayGet($arrayCondition, $stringConditionType, null);

                // Loop over the statements
                foreach ($arrayStatements as $arrayStatement) {
                    // Statement type ( operators or more conditions )
                    $stringStatementType = ___::arrayFirstKey($arrayStatement);

                    // Statement data for arguments and results instructions
                    $arrayStatementData = ___::arrayGet($arrayStatement, $stringStatementType, null);

                    // what type, more conditions?? or operators??
                    switch ($stringStatementType) {
                        case 'condition':
                            $mixedResult = self::parse($fiveCode, [$arrayStatementData]);
                            break;
                        case 'conditions':
                            $mixedResult = self::parse($fiveCode, $arrayStatementData);
                            break;
                        default:
                            // Get the arguments
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
                            $mixedResult = ___::is($mixedLeft, $stringStatementType, $mixedRight);
                    }

                    // Statement specific instructions???
                    if (
                        (
                            true === $mixedResult
                            && is_array(
                                $arrayInstructions = ___::arrayGet($arrayStatementData, 'true', null)
                            )
                        )
                        || (
                            true !== $mixedResult
                            && is_array(
                                $arrayInstructions = ___::arrayGet($arrayStatementData, 'false', null)
                            )
                        )
                    ) {
                        $mixedResult = $fiveCode->parse($arrayInstructions);
                    }

                    // break when any | all did not get what's expected
                    if (
                        (
                            $mixedResult
                            && 'any' === $stringConditionType
                        )
                        || (
                            !$mixedResult
                            && 'all' === $stringConditionType
                        )
                    ) {
                        break;
                    }
                }

                // If we have the results we expected based on type then do we have more instructions??
                if (
                    (
                        true === $mixedResult
                        && is_array(
                            $arrayInstructions = ___::arrayGet($arrayCondition, 'true', null)
                        )
                    )
                    || (
                        true !== $mixedResult
                        && is_array(
                            $arrayInstructions = ___::arrayGet($arrayCondition, 'false', null)
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