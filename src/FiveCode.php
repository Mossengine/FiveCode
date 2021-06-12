<?php namespace Mossengine\FiveCode;

use Mossengine\FiveCode\Exceptions\EvaluationException;
use Mossengine\FiveCode\Exceptions\FunctionException;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class FiveCode
 * @package Mossengine\FiveCode
 */
class FiveCode
{

    /**
     * @var array
     */
    private $arrayFunctions = [];

    /**
     * @param $stringName
     * @param $mixedCallable
     */
    public function functionsSet($stringName, $mixedCallable) {
        ___::arraySet($this->arrayFunctions, $stringName, $mixedCallable);
    }

    /**
     * @param $stringName
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function functionsGet($stringName, $mixedDefault = null) {
        return ___::arrayGet($this->arrayFunctions, $stringName, $mixedDefault);
    }

    /**
     * @return array
     */
    public function functionsAll() : array {
        return $this->arrayFunctions;
    }

    /**
     * @param $stringName
     */
    public function functionsForget($stringName) {
        ___::arrayForget($this->arrayFunctions, $stringName);
    }

    /**
     * @var null
     */
    private $arrayFunctionsAllowed = [];

    /**
     * @param $stringName
     * @return bool
     */
    public function functionsAllowed($stringName) : bool {
        return true === (
            ___::arrayGet(
                $this->arrayFunctionsAllowed,
                $stringName,
                ___::arrayGet(
                    $this->arrayFunctionsAllowed,
                    '*',
                    false
                )
            )
        );
    }

    /**
     * @var array
     */
    private $arrayVariables = [];

    /**
     * @param $stringPath
     * @param $mixedValue
     */
    public function variablesSet($stringPath, $mixedValue) {
        if ($this->variablesAllowed($stringPath, 'set')) {
            ___::arraySet($this->arrayVariables, $stringPath, $mixedValue);
        }
    }

    /**
     * @param $stringPath
     * @param $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function variablesGet($stringPath, $mixedDefault = null) {
        if ($this->variablesAllowed($stringPath, 'get')) {
            return ___::arrayGet($this->arrayVariables, $stringPath, $mixedDefault);
        }
        return null;
    }

    /**
     * @return array
     */
    public function variablesAll() : array {
        if (
            empty($this->arrayVariablesAllowed)
            || (
                ['*'] === array_keys($this->arrayVariablesAllowed)
                && $this->variablesAllowed('*', 'get')
            )
        ) {
            return $this->arrayVariables;
        }
        return [];
    }

    /**
     * @param $stringPath
     */
    public function variablesForget($stringPath) {
        if ($this->variablesAllowed($stringPath, 'forget')) {
            ___::arrayForget($this->arrayVariables, $stringPath);
        }
    }

    /**
     * @var null
     */
    private $arrayVariablesAllowed = [];

    /**
     * @param string $stringVariableNameOrPath
     * @param string $stringAction
     * @return bool
     */
    public function variablesAllowed(string $stringVariableNameOrPath, string $stringAction = '*') : bool {
        return (
            true === (
                ___::arrayGet(
                    $this->arrayVariablesAllowed,
                    $stringVariableNameOrPath . '.' . $stringAction,
                    ___::arrayGet(
                        $this->arrayVariablesAllowed,
                        $stringVariableNameOrPath,
                        ___::arrayGet(
                            $this->arrayVariablesAllowed,
                            '*.' . $stringAction,
                            ___::arrayGet(
                                $this->arrayVariablesAllowed,
                                '*',
                                true
                            )
                        )
                    )
                )
            )
        );
    }

    /**
     * FiveCode constructor.
     * @param array $arrayParameters
     */
    public function __construct(array $arrayParameters = []) {
        $this->arrayFunctions = ___::arrayGet($arrayParameters, 'functions.default', []);
        $this->arrayFunctionsAllowed = ___::arrayGet($arrayParameters, 'functions.allowed', []);

        $this->arrayVariables = ___::arrayGet($arrayParameters, 'variables.default', []);
        $this->arrayVariablesAllowed = ___::arrayGet($arrayParameters, 'variables.allowed', []);
    }

    /**
     * @param array $arrayParameters
     * @return static
     */
    public static function make(array $arrayParameters = []) : self {
        return new self($arrayParameters);
    }

    /**
     * @var int
     */
    private $intEvaluationsRecursions = 0;

    /**
     * @param array $arrayEvaluations
     * @param bool $boolReturnSelf
     * @return bool|mixed|FiveCode|null
     * @throws EvaluationException
     * @throws FunctionException
     */
    public function evaluate(array $arrayEvaluations = [], bool $boolReturnSelf = true) {
        $this->intEvaluationsRecursions++;
        $mixedResult = null;

        if ($this->intEvaluationsRecursions < 10) {
            foreach ($arrayEvaluations as $arrayEvaluation) {
                $stringEvaluationType = ___::arrayFirstKey($arrayEvaluation);
                $mixedEvaluationData = ___::arrayGet($arrayEvaluation, $stringEvaluationType, []);
                switch ($stringEvaluationType) {
                    case 'evaluate':
                        $mixedResult = $this->evaluate([$mixedEvaluationData], false);
                        break;
                    case 'evaluates':
                        $mixedResult = $this->evaluate($mixedEvaluationData, false);
                        break;
                    case 'variable':
                        $mixedResult = $this->variables([$mixedEvaluationData]);
                        break;
                    case 'variables':
                        $mixedResult = $this->variables($mixedEvaluationData);
                        break;
                    case 'function':
                        $this->functions([$mixedEvaluationData]);
                        break;
                    case 'functions':
                        $this->functions($mixedEvaluationData);
                        break;
                    case 'condition':
                        $mixedResult = $this->conditions([$mixedEvaluationData]);
                        break;
                    case 'conditions':
                        $mixedResult = $this->conditions($mixedEvaluationData);
                        break;
                    case 'execute':
                        $mixedResult = $this->executes([$mixedEvaluationData]);
                        break;
                    case 'executes':
                        $mixedResult = $this->executes($mixedEvaluationData);
                        break;
                    default:
                        throw new EvaluationException('Invalid evaluation key : ' . $stringEvaluationType);
                }
            }
        }
        $this->intEvaluationsRecursions--;
        $this->variablesSet('return', $mixedResult);
        return $boolReturnSelf ? $this : $mixedResult;
    }

    /**
     * @param array $arrayVariables
     * @return array|\ArrayAccess|mixed|null
     * @throws EvaluationException
     */
    public function variables(array $arrayVariables = []) {
        $mixedResult = null;

        foreach ($arrayVariables as $arrayVariable) {
            $stringVariableType = ___::arrayFirstKey($arrayVariable);
            $mixedVariableData = ___::arrayGet($arrayVariable, $stringVariableType, null);
            $mixedVariableKey = ___::arrayGet($mixedVariableData, 'key', ___::arrayFirstKey($mixedVariableData));
            $mixedVariableValueOrDefault = ___::arrayGet($mixedVariableData, 'value', ___::arrayFirstValue($mixedVariableData));

            switch ($stringVariableType) {
                case 'all':
                    $mixedResult = $this->variablesAll();
                    break;
                case 'get':
                    $mixedResult = $this->variablesGet($mixedVariableKey, $mixedVariableValueOrDefault);
                    break;
                case 'set':
                    $this->variablesSet($mixedVariableKey, $mixedVariableValueOrDefault);
                    $mixedResult = $this->variablesGet('return', $mixedResult);
                    break;
                case 'forget':
                    $this->variablesForget($mixedVariableKey);
                    $mixedResult = $this->variablesGet('return', $mixedResult);
                    break;
                default:
                    throw new EvaluationException('Invalid variable type : ' . $stringVariableType);
            }
        }

        $this->variablesSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayFunctions
     * @throws FunctionException
     */
    private function functions(array $arrayFunctions = []) {
        foreach ($arrayFunctions as $arrayFunction) {
            $stringFunctionType = ___::arrayFirstKey($arrayFunction);
            $mixedFunctionData = ___::arrayGet($arrayFunction, $stringFunctionType, null);
            $mixedFunctionName = ___::arrayGet($mixedFunctionData, 'key', null);
            $mixedFunctionCallable = ___::arrayGet($mixedFunctionData, 'callable', $mixedFunctionData);

            if (is_null($mixedFunctionName)) {
                $mixedFunctionName = $stringFunctionType;
                $stringFunctionType = 'set';
            }

            switch ($stringFunctionType) {
                case 'set':
                    $this->functionsSet($mixedFunctionName, $mixedFunctionCallable);
                    break;
                case 'forget':
                    $this->functionsForget($mixedFunctionName);
                    break;
                default:
                    throw new FunctionException('Invalid function type : ' . $stringFunctionType);
            }
        }
    }

    /**
     * @var int
     */
    private $intConditionsRecursions = 0;

    /**
     * @param array $arrayConditions
     * @return bool|null
     * @throws EvaluationException
     * @throws FunctionException
     */
    private function conditions(array $arrayConditions = []) {
        $this->intConditionsRecursions++;
        $mixedResult = null;

        if ($this->intConditionsRecursions < 10) {
            foreach ($arrayConditions as $arrayCondition) {
                $stringConditionType = ___::arrayFirstKey($arrayCondition);
                $arrayConditionData = ___::arrayGet($arrayCondition, $stringConditionType, null);
                $arrayStatements = ___::arrayGet($arrayConditionData, 'statements', null);

                foreach ($arrayStatements as $arrayStatement) {
                    $stringStatementType = ___::arrayFirstKey($arrayStatement);
                    $arrayStatementData = ___::arrayGet($arrayStatement, $stringStatementType, null);

                    if (in_array($stringStatementType, ['condition', 'conditions'])) {
                        $mixedResult = $this->conditions($arrayStatementData);
                    } else {
                        $arrayArguments = array_map(
                            function (array $arrayArgument) {
                                $stringArgumentKey = ___::arrayFirstKey($arrayArgument);
                                $mixedArgumentValue = ___::arrayGet($arrayArgument, $stringArgumentKey, null);
                                switch ($stringArgumentKey) {
                                    case 'variable':
                                        return $this->variablesGet($mixedArgumentValue, null);
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
                                    $arrayEvaluates = ___::arrayGet($arrayStatementData, 'true', null)
                                )
                            )
                            || (
                                !$mixedResult
                                && is_array(
                                    $arrayEvaluates = ___::arrayGet($arrayStatementData, 'false', null)
                                )
                            )
                        ) {
                            $mixedResult = $this->evaluate($arrayEvaluates, false);
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
                            $arrayEvaluates = ___::arrayGet($arrayConditionData, 'true', null)
                        )
                    )
                    || (
                        !$mixedResult
                        && is_array(
                            $arrayEvaluates = ___::arrayGet($arrayConditionData, 'false', null)
                        )
                    )
                ) {
                    $mixedResult = $this->evaluate($arrayEvaluates, false);
                }
            }
        }
        $this->intConditionsRecursions--;
        $this->variablesSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayExecutes
     * @return false|mixed|null
     */
    private function executes(array $arrayExecutes = []) {
        $mixedResult = null;
        foreach ($arrayExecutes as $arrayExecute) {
            $stringFunctionName = ___::arrayFirstKey($arrayExecute);
            $mixedFunctionData = ___::arrayGet($arrayExecute, $stringFunctionName, null);

            $callable = $this->functionsGet($stringFunctionName, null);

//            echo 'FUNC: ' . $stringFunctionName . PHP_EOL;
//            echo 'CALLABLE: ' . (is_callable($callable) ? 'yes' : 'no') . PHP_EOL;
//            echo 'ALLOWED: ' . ($this->functionsAllowed($stringFunctionName) ? 'yes' : 'no') . PHP_EOL;
//            echo 'EXISTS: ' . (function_exists($stringFunctionName) ? 'yes' : 'no') . PHP_EOL;

            $mixedResult = call_user_func_array(
                (
                    is_callable($callable)
                        ? $callable
                        : (
                            $this->functionsAllowed($stringFunctionName)
                            && function_exists($stringFunctionName)
                                ? $stringFunctionName
                                : function() { return null; }
                        )
                ),
                array_map(
                    function (array $arrayArgument) {
                        $stringArgumentKey = ___::arrayFirstKey($arrayArgument);
                        $mixedArgumentValue = ___::arrayGet($arrayArgument, $stringArgumentKey, null);
                        switch ($stringArgumentKey) {
                            case 'variable':
                                return $this->variablesGet($mixedArgumentValue, null);
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
                            $this->variablesSet($mixedReturnValue, $mixedResult);
                            break;
                    }
                }
            }

        }

        $this->variablesSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function return($mixedDefault = null) {
        return $this->variablesGet('return', $mixedDefault);
    }
}