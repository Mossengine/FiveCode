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
     * @param array|null $arrayFunctions
     * @return $this|array
     */
    public function functions(array $arrayFunctions = null) {
        if (is_null($arrayFunctions)) {
            return $this->arrayFunctions;
        }
        $this->arrayFunctions = $arrayFunctions;
        return $this;
    }

    /**
     * @param $stringName
     * @param $mixedCallable
     */
    public function functionSet($stringName, $mixedCallable) {
        ___::arraySet($this->arrayFunctions, $stringName, $mixedCallable);
    }

    /**
     * @param $stringName
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function functionGet($stringName, $mixedDefault = null) {
        return ___::arrayGet($this->arrayFunctions, $stringName, $mixedDefault);
    }

    /**
     * @param $stringName
     */
    public function functionForget($stringName) {
        ___::arrayForget($this->arrayFunctions, $stringName);
    }

    /**
     * @var null
     */
    private $arrayFunctionsAllowed = [];

    /**
     * @param array $arrayFunctionsAllowed
     * @return $this
     */
    public function functionsAllowed(array $arrayFunctionsAllowed = []) : self {
        $this->arrayFunctionsAllowed = $arrayFunctionsAllowed;
        return $this;
    }

    /**
     * @param $stringName
     * @return bool
     */
    public function isFunctionAllowed($stringName) : bool {
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
     * @param array|null $arrayVariables
     * @return $this|array
     */
    public function variables(array $arrayVariables = null) {
        if (is_null($arrayVariables)) {
            return $this->arrayVariables;
        }
        $this->arrayVariables = $arrayVariables;
        return $this;
    }

    /**
     * @param $stringPath
     * @param $mixedValue
     */
    public function variableSet($stringPath, $mixedValue) {
        ___::arraySet($this->arrayVariables, $stringPath, $mixedValue);
    }

    /**
     * @param $stringPath
     * @param $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function variableGet($stringPath, $mixedDefault = null) {
        return ___::arrayGet($this->arrayVariables, $stringPath, $mixedDefault);
    }

    /**
     * @param $stringPath
     */
    public function variableForget($stringPath) {
        ___::arrayForget($this->arrayVariables, $stringPath);
    }

    /**
     * @var null
     */
    private $arrayVariablesAllowed = [];

    /**
     * @param array $arrayVariablesAllowed
     * @return $this
     */
    public function variablesAllowed(array $arrayVariablesAllowed = []) : self {
        $this->arrayVariablesAllowed = $arrayVariablesAllowed;
        return $this;
    }

    /**
     * @param string $stringVariableNameOrPath
     * @param string $stringAction
     * @return bool
     */
    public function isVariableAllowed(string $stringVariableNameOrPath, string $stringAction = '*') : bool {
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
        $this->functions(___::arrayGet($arrayParameters, 'functions.default', []))
            ->functionsAllowed(___::arrayGet($arrayParameters, 'functions.allowed', []))
            ->variables(___::arrayGet($arrayParameters, 'variables.default', []))
            ->variablesAllowed(___::arrayGet($arrayParameters, 'variables.allowed', []));
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
     * @param array $arrayInstructions
     * @return $this
     * @throws EvaluationException
     * @throws FunctionException
     */
    public function evaluate(array $arrayInstructions = []) : self {
        $this->parseInstructions($arrayInstructions);
        return $this;
    }

    /**
     * @param array $arrayInstructions
     * @return bool|mixed|FiveCode|null
     * @throws EvaluationException
     * @throws FunctionException
     */
    public function parseInstructions(array $arrayInstructions = []) {
        $this->intEvaluationsRecursions++;
        $mixedResult = null;

        if ($this->intEvaluationsRecursions < 10) {
            foreach ($arrayInstructions as $arrayEvaluation) {
                $stringEvaluationType = ___::arrayFirstKey($arrayEvaluation);
                $mixedEvaluationData = ___::arrayGet($arrayEvaluation, $stringEvaluationType, []);
                switch ($stringEvaluationType) {
                    case 'instruction':
                        $mixedResult = $this->parseInstructions([$mixedEvaluationData]);
                        break;
                    case 'instructions':
                        $mixedResult = $this->parseInstructions($mixedEvaluationData);
                        break;
                    case 'variable':
                        $mixedResult = $this->parseVariables([$mixedEvaluationData]);
                        break;
                    case 'variables':
                        $mixedResult = $this->parseVariables($mixedEvaluationData);
                        break;
                    case 'function':
                        $this->parseFunctions([$mixedEvaluationData]);
                        break;
                    case 'functions':
                        $this->parseFunctions($mixedEvaluationData);
                        break;
                    case 'condition':
                        $mixedResult = $this->parseConditions([$mixedEvaluationData]);
                        break;
                    case 'conditions':
                        $mixedResult = $this->parseConditions($mixedEvaluationData);
                        break;
                    case 'execute':
                        $mixedResult = $this->parseExecutes([$mixedEvaluationData]);
                        break;
                    case 'executes':
                        $mixedResult = $this->parseExecutes($mixedEvaluationData);
                        break;
                    default:
                        throw new EvaluationException('Invalid evaluation key : ' . $stringEvaluationType);
                }
            }
        }
        $this->intEvaluationsRecursions--;
        $this->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayVariables
     * @return array|\ArrayAccess|mixed|null
     * @throws EvaluationException
     */
    public function parseVariables(array $arrayVariables = []) {
        $mixedResult = null;

        foreach ($arrayVariables as $arrayVariable) {
            $stringVariableType = ___::arrayFirstKey($arrayVariable);
            $mixedVariableData = ___::arrayGet($arrayVariable, $stringVariableType, null);
            $mixedVariableKey = ___::arrayGet($mixedVariableData, 'key', ___::arrayFirstKey($mixedVariableData));
            $mixedVariableValueOrDefault = ___::arrayGet($mixedVariableData, 'value', ___::arrayFirstValue($mixedVariableData));

            switch ($stringVariableType) {
                case 'all':
                    $mixedResult = (
                        empty($this->arrayVariablesAllowed)
                        || (
                            ['*'] === array_keys($this->arrayVariablesAllowed)
                            && $this->isVariableAllowed('*', 'get')
                        )
                            ? $this->variables()
                            : []
                    );
                    break;
                case 'get':
                    $mixedResult = (
                        $this->isVariableAllowed($mixedVariableKey, 'get')
                            ? $this->variableGet($mixedVariableKey, $mixedVariableValueOrDefault)
                            : null
                    );
                    break;
                case 'set':
                    if ($this->isVariableAllowed($mixedVariableKey, 'set')) {
                        $this->variableSet($mixedVariableKey, $mixedVariableValueOrDefault);
                    }
                    $mixedResult = $this->variableGet('return', $mixedResult);
                    break;
                case 'forget':
                    if ($this->isVariableAllowed($mixedVariableKey, 'forget')) {
                        $this->variableForget($mixedVariableKey);
                    }
                    $mixedResult = $this->variableGet('return', $mixedResult);
                    break;
                default:
                    throw new EvaluationException('Invalid variable type : ' . $stringVariableType);
            }
        }

        $this->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayFunctions
     * @throws FunctionException
     */
    private function parseFunctions(array $arrayFunctions = []) {
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
                    $this->functionSet($mixedFunctionName, $mixedFunctionCallable);
                    break;
                case 'forget':
                    $this->functionForget($mixedFunctionName);
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
    private function parseConditions(array $arrayConditions = []) {
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
                        $mixedResult = $this->parseConditions($arrayStatementData);
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
                            $mixedResult = $this->parseInstructions($arrayInstructions);
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
                    $mixedResult = $this->parseInstructions($arrayInstructions);
                }
            }
        }
        $this->intConditionsRecursions--;
        $this->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayExecutes
     * @return false|mixed|null
     */
    private function parseExecutes(array $arrayExecutes = []) {
        $mixedResult = null;
        foreach ($arrayExecutes as $arrayExecute) {
            $stringFunctionName = ___::arrayFirstKey($arrayExecute);
            $mixedFunctionData = ___::arrayGet($arrayExecute, $stringFunctionName, null);

            $callable = $this->functionGet($stringFunctionName, null);

//            echo 'FUNC: ' . $stringFunctionName . PHP_EOL;
//            echo 'CALLABLE: ' . (is_callable($callable) ? 'yes' : 'no') . PHP_EOL;
//            echo 'ALLOWED: ' . ($this->functionsAllowed($stringFunctionName) ? 'yes' : 'no') . PHP_EOL;
//            echo 'EXISTS: ' . (function_exists($stringFunctionName) ? 'yes' : 'no') . PHP_EOL;

            $mixedResult = call_user_func_array(
                (
                    is_callable($callable)
                        ? $callable
                        : (
                            $this->isFunctionAllowed($stringFunctionName)
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
                                return (
                                    $this->isVariableAllowed($mixedArgumentValue, 'get')
                                        ? $this->variableGet($mixedArgumentValue, null)
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
                            if ($this->isVariableAllowed($mixedReturnValue, 'set')) {
                                $this->variableSet($mixedReturnValue, $mixedResult);
                            }
                            break;
                    }
                }
            }

        }

        $this->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function return($mixedDefault = null) {
        return $this->variableGet('return', $mixedDefault);
    }
}