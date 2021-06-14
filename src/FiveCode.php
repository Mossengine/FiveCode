<?php namespace Mossengine\FiveCode;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\Exceptions\FunctionException;
use Mossengine\FiveCode\Helpers\___;
use Mossengine\FiveCode\Parsers\ModuleAbstract;
use Mossengine\FiveCode\Parsers\Variables;

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
     * @param array|null $arrayFunctionsAllowed
     * @return $this|array|null
     */
    public function functionsAllowed(array $arrayFunctionsAllowed = null) {
        if (is_null($arrayFunctionsAllowed)) {
            return $this->arrayFunctionsAllowed;
        }
        $this->arrayFunctionsAllowed = $arrayFunctionsAllowed;
        return $this;
    }

    /**
     * @param $stringFunctionName
     * @return bool
     */
    public function isFunctionAllowed($stringFunctionName) : bool {
        return true === (
            ___::arrayGet(
                $this->arrayFunctionsAllowed,
                $stringFunctionName,
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
     * @param array|null $arrayVariablesAllowed
     * @return $this|array|null
     */
    public function variablesAllowed(array $arrayVariablesAllowed = null) {
        if (is_null($arrayVariablesAllowed)) {
            return $this->arrayVariablesAllowed;
        }
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
     * @var array
     */
    private $arrayParsers = [];

    /**
     * @param array $stringParserNamespaces
     * @return $this
     */
    public function parsers(array $stringParserNamespaces = []) : self {
        foreach ($stringParserNamespaces as $stringParserNamespace) {
            $this->parserAdd($stringParserNamespace);
        }
        return $this;
    }

    /**
     * @param $stringParserNamespace
     */
    public function parserAdd($stringParserNamespace) {
        foreach ($stringParserNamespace::register() as $stringKey => $callable) {
            $this->parserSet($stringKey, $callable);
        }
    }

    /**
     * @param $stringKey
     * @param $callable
     */
    public function parserSet($stringKey, $callable) {
        if (is_callable($callable)) {
            ___::arraySet($this->arrayParsers, $stringKey, $callable);
        }
    }

    /**
     * @param $stringKey
     * @return array|\ArrayAccess|mixed|null
     */
    public function parserGet($stringKey) {
        return ___::arrayGet($this->arrayParsers, $stringKey, null);
    }

    /**
     * @param $stringKey
     */
    public function parserForget($stringKey) {
        ___::arrayForget($this->arrayParsers, $stringKey);
    }

    /**
     * @var null
     */
    private $arrayParsersAllowed = [];

    /**
     * @param array|null $arrayParsersAllowed
     * @return $this|array|null
     */
    public function parsersAllowed(array $arrayParsersAllowed = null) {
        if (is_null($arrayParsersAllowed)) {
            return $this->arrayParsersAllowed;
        }
        $this->arrayParsersAllowed = $arrayParsersAllowed;
        return $this;
    }

    /**
     * @param string $stringParserName
     * @return bool
     */
    public function isParserAllowed(string $stringParserName) : bool {
        return true === (
            ___::arrayGet(
                $this->arrayParsersAllowed,
                $stringParserName,
                ___::arrayGet(
                    $this->arrayParsersAllowed,
                    '*',
                    true
                )
            )
        );
    }

    /**
     * FiveCode constructor.
     * @param array $arrayParameters
     */
    public function __construct(array $arrayParameters = []) {
        $this
            // Parsers
            ->parsers(array_merge(
                [
                    'variables' => Variables::class
                ],
                ___::arrayGet($arrayParameters, 'parsers.default', [])
            ))
            ->parsersAllowed(___::arrayGet($arrayParameters, 'parsers.allowed', []))

            // Functions
            ->functions(___::arrayGet($arrayParameters, 'functions.default', []))
            ->functionsAllowed(___::arrayGet($arrayParameters, 'functions.allowed', []))

            // Variables
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
     * @throws InstructionException
     * @throws FunctionException
     */
    public function evaluate(array $arrayInstructions = []) : self {
        $this->parseInstructions($arrayInstructions);
        return $this;
    }

    /**
     * @param array $arrayInstructions
     * @return bool|mixed|FiveCode|null
     * @throws InstructionException
     * @throws FunctionException
     */
    public function parseInstructions(array $arrayInstructions = []) {
        $this->intEvaluationsRecursions++;
        $mixedResult = null;

        if ($this->intEvaluationsRecursions < 10) {
            foreach ($arrayInstructions as $arrayEvaluation) {
                $stringEvaluationType = ___::arrayFirstKey($arrayEvaluation);
                if (!$this->isParserAllowed($stringEvaluationType)) {
                    throw new InstructionException('Disabled parser : ' . $stringEvaluationType);
                }
                $mixedEvaluationData = ___::arrayGet($arrayEvaluation, $stringEvaluationType, []);
                switch ($stringEvaluationType) {
                    case 'instruction':
                        $mixedResult = $this->parseInstructions([$mixedEvaluationData]);
                        break;
                    case 'instructions':
                        $mixedResult = $this->parseInstructions($mixedEvaluationData);
                        break;
                    case 'value':
                        $mixedResult = $this->parseValues([$mixedEvaluationData]);
                        break;
                    case 'values':
                        $mixedResult = $this->parseValues($mixedEvaluationData);
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
                        /** @var ModuleAbstract $module */
                        $parser = $this->parserGet($stringEvaluationType);
                        if (is_callable($parser)) {
                            $mixedResult = call_user_func_array($parser, [$this, $mixedEvaluationData]);
                        } else {
                            throw new InstructionException('Invalid parser : ' . $stringEvaluationType);
                        }
                }
            }
        }
        $this->intEvaluationsRecursions--;
        $this->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param array $arrayValues
     * @return null
     */
    public function parseValues(array $arrayValues = []) {
        $mixedResult = null;

        foreach ($arrayValues as $mixedValue) {
            $this->variableSet('return', ($mixedResult = $mixedValue));
        }

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
     * @throws InstructionException
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