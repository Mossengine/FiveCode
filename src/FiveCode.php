<?php namespace Mossengine\FiveCode;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\Functions\Maths;
use Mossengine\FiveCode\Helpers\___;
use Mossengine\FiveCode\Parsers\Conditions;
use Mossengine\FiveCode\Parsers\Executes;
use Mossengine\FiveCode\Parsers\Functions;
use Mossengine\FiveCode\Parsers\Iterators;
use Mossengine\FiveCode\Parsers\Values;
use Mossengine\FiveCode\Parsers\Variables;

/**
 * Class FiveCode
 * @package Mossengine\FiveCode
 */
class FiveCode
{

    const LOOP_INSTRUCTIONS_LIMIT = 100;
    const LOOP_INSTRUCTION_LIMIT = 100;

    /**
     * @var bool
     */
    private $boolDebug = false;

    /**
     * @param bool|null $boolDebug
     * @return $this|bool
     */
    public function isDebug(bool $boolDebug = null) {
        if (!is_bool($boolDebug)) {
            return $this->boolDebug;
        }
        $this->boolDebug = $boolDebug;
        return $this;
    }

    /**
     * @var array
     */
    private $arrayLoopTracking = [];

    /**
     * @param string $stringLoopName
     * @param int $intAdjustment
     */
    public function loopAdjust(string $stringLoopName, int $intAdjustment = 0) {
        $this->loopSet(
            $stringLoopName,
            $this->loopGet($stringLoopName) + $intAdjustment
        );
    }

    /**
     * @param string $stringLoopName
     * @param int $intDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function loopGet(string $stringLoopName, int $intDefault = 0) : int {
        return ___::arrayGet(
            $this->arrayLoopTracking,
            $stringLoopName,
            $intDefault
        );
    }

    /**
     * @param string $stringLoopName
     * @param int $intAmount
     */
    public function loopSet(string $stringLoopName, int $intAmount = 0) {
        ___::arraySet(
            $this->arrayLoopTracking,
            $stringLoopName,
            $intAmount
        );
    }

    /**
     * @param string $stringLoopName
     * @param int $intUp
     */
    public function loopUp(string $stringLoopName, int $intUp = 1) {
        $this->loopAdjust($stringLoopName, $intUp);
    }

    /**
     * @param string $stringLoopName
     * @param int $intUp
     */
    public function loopDown(string $stringLoopName, int $intUp = -1) {
        $this->loopAdjust($stringLoopName, $intUp);
    }

    /**
     * @param string $stringLoopName
     * @param int $intAmount
     * @return bool
     */
    public function isLoopOver(string $stringLoopName, int $intAmount = 0) : bool {
        return $this->loopGet($stringLoopName) > $intAmount;
    }

    /**
     * @param string $stringLoopName
     * @param int $intAmount
     * @return bool
     */
    public function isLoopUnder(string $stringLoopName, int $intAmount = 0) : bool {
        return $this->loopGet($stringLoopName) < $intAmount;
    }

    /**
     * @var array
     */
    private $arrayFunctions = [];

    /**
     * @param array|null $arrayFunctions
     * @return $this|array
     */
    public function functionsOld(array $arrayFunctions = null) {
        if (is_null($arrayFunctions)) {
            return $this->arrayFunctions;
        }
        $this->arrayFunctions = $arrayFunctions;
        return $this;
    }

    /**
     * @param array|null $arrayFunctionNamespace
     * @return $this|array
     */
    public function functions(array $arrayFunctionNamespace = null) {
        if (is_null($arrayFunctionNamespace)) {
            return $this->arrayFunctions;
        }
        foreach ($arrayFunctionNamespace as $stringFunctionKey => $stringFunctionNamespace) {
            $this->functionAdd($stringFunctionKey, $stringFunctionNamespace);
        }
        return $this;
    }

    /**
     * @param $stringFunctionKey
     * @param $stringFunctionNamespace
     */
    public function functionAdd($stringFunctionKey, $stringFunctionNamespace) {
        if (
            !is_callable($stringFunctionNamespace)
        ) {
            foreach ($stringFunctionNamespace::register() as $stringKey => $callable) {
                $this->functionSet($stringKey, $callable);
            }
            $this->settingsMerge($stringFunctionNamespace::settings());
        } else if (
            is_string($stringFunctionKey)
            && is_callable($stringFunctionNamespace)
        ) {
            $this->functionSet($stringFunctionKey, $stringFunctionNamespace);
        }
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
     * @return $this
     */
    public function variableSet($stringPath, $mixedValue) : self {
        ___::arraySet($this->arrayVariables, $stringPath, $mixedValue);
        return $this;
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
     * @return $this
     */
    public function variableForget($stringPath) : self {
        ___::arrayForget($this->arrayVariables, $stringPath);
        return $this;
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
     * @param array $arrayParserNamespaces
     * @return $this
     */
    public function parsers(array $arrayParserNamespaces = []) : self {
        foreach ($arrayParserNamespaces as $stringParserNamespace) {
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
        $this->settingsMerge($stringParserNamespace::settings());
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
     * @var array
     */
    private $arraySettings = [
        '_fivecode' => [
            'instructions' => [
                'max' => [
                    'iteration' => 100,
                    'duration' => 30
                ]
            ],
            'instruction' => [
                'max' => [
                    'iteration' => 100,
                    'duration' => 30
                ]
            ],
        ]
    ];

    /**
     * @param array|null $arraySettings
     * @return $this|array
     */
    public function settings(array $arraySettings = null) {
        if (is_null($arraySettings)) {
            return $this->arraySettings;
        }
        $this->settingsMerge($arraySettings);
        return $this;
    }

    /**
     * @param $stringPath
     * @param $mixedValue
     * @return $this
     */
    public function settingSet($stringPath, $mixedValue) : self {
        ___::arraySet($this->arraySettings, $stringPath, $mixedValue);
        return $this;
    }

    /**
     * @param $stringPath
     * @param $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function settingGet($stringPath, $mixedDefault = null) {
        return ___::arrayGet($this->arraySettings, $stringPath, $mixedDefault);
    }

    /**
     * @param $stringPath
     * @return $this
     */
    public function settingForget($stringPath) : self {
        ___::arrayForget($this->arraySettings, $stringPath);
        return $this;
    }

    /**
     * @param array $arraySettings
     * @return $this
     */
    public function settingsMerge(array $arraySettings = []) : self {
        $this->arraySettings = array_replace_recursive(
            $this->arraySettings,
            $arraySettings
        );
        return $this;
    }

    /**
     * FiveCode constructor.
     * @param array $arrayParameters
     */
    public function __construct(array $arrayParameters = []) {
        $this
            // Debug
            ->isDebug(false !== ___::arrayGet($arrayParameters, 'debug', false))

            // Parsers
            ->parsers(array_merge(
                [
                    'values' => Values::class,
                    'variables' => Variables::class,
                    'conditions' => Conditions::class,
                    'executes' => Executes::class,
                    'iterators' => Iterators::class
                ],
                ___::arrayGet($arrayParameters, 'parsers.include', [])
            ))
            ->parsersAllowed(___::arrayGet($arrayParameters, 'parsers.allowed', []))

            // Functions
            ->functions(array_merge(
                [
                    'maths' => Maths::class
                ],
                ___::arrayGet($arrayParameters, 'functions.include', [])
            ))
            ->functionsAllowed(___::arrayGet($arrayParameters, 'functions.allowed', []))

            // Variables
            ->variables(___::arrayGet($arrayParameters, 'variables.include', []))
            ->variablesAllowed(___::arrayGet($arrayParameters, 'variables.allowed', []))

            // Settings
            ->settings(___::arrayGet($arrayParameters, 'settings.include', []));
    }

    /**
     * @param array $arrayParameters
     * @return static
     */
    public static function make(array $arrayParameters = []) : self {
        return new self($arrayParameters);
    }

    /**
     * @param array $arrayInstructions
     * @return $this
     * @throws InstructionException
     */
    public function evaluate(array $arrayInstructions = []) : self {
        $this->instructions($arrayInstructions);
        return $this;
    }

    /**
     * @param mixed $mixedInstructionsOrInstruction
     * @return false|mixed|null
     * @throws InstructionException
     */
    public function instructions($mixedInstructionsOrInstruction = []) {
        $this->debug('instructions', $mixedInstructionsOrInstruction);
        $this->loopUp('instructions');
        $mixedResult = $this->result();
        if (
            $this->isLoopUnder(
                'instructions',
                $this->settingGet('_fivecode.instructions.max.iteration')
            )
            // TODO: Come back and add max duration checks
        ) {
            foreach (
                (
                    !is_array($mixedInstructionsOrInstruction)
                    || ___::arrayIsAssociative($mixedInstructionsOrInstruction)
                        ? [$mixedInstructionsOrInstruction]
                        : $mixedInstructionsOrInstruction
                )
                as $mixedInstruction
            ) {
                $mixedResult = $this->instruction($mixedInstruction);
            }
        }
        $this->loopDown('instructions');

        return $this->result($mixedResult);
    }

    /**
     * @param mixed $mixedDataOrArray
     * @return false|mixed|null
     * @throws InstructionException
     */
    public function instruction($mixedDataOrArray = null) {
        $this->debug('instruction', $mixedDataOrArray);
        $mixedResult = $this->result();
        if (!is_array($mixedDataOrArray)) {
            $this->debug('literal', $mixedDataOrArray);
            $mixedResult = $mixedDataOrArray;
        } else if (!empty($mixedDataOrArray)) {
            $this->loopUp('instruction');
            if (!___::arrayIsAssociative($mixedDataOrArray)) {
                $mixedResult = $this->instructions($mixedDataOrArray);
            } else if (
                $this->isLoopUnder(
                    'instruction',
                    $this->settingGet('_fivecode.instruction.max.iteration')
                )
                // TODO: Come back and add max duration checks
            ) {
                $stringInstructionType = ___::arrayFirstKey($mixedDataOrArray);
                $mixedData = ___::arrayGet($mixedDataOrArray, $stringInstructionType, []);

                if (!$this->isParserAllowed($stringInstructionType)) {
                    throw new InstructionException('Disabled parser : ' . $stringInstructionType);
                }
                if (!is_callable($parser = $this->parserGet($stringInstructionType))) {
                    throw new InstructionException('Invalid parser : ' . $stringInstructionType);
                }

                $this->debug($stringInstructionType . ' - data : ', $mixedData);

                $mixedResult = call_user_func_array(
                    $parser,
                    [
                        $this,
                        $mixedData
                    ]
                );

                $this->debug($stringInstructionType . ' - returned : ', $mixedResult);
            }
            $this->loopDown('instruction');
        }

        return $this->result($mixedResult);
    }

    /**
     * @param string $stringMessage
     * @param mixed $mixedData
     * @return $this
     */
    public function debug(string $stringMessage, $mixedData = '33b8b54a-fc92-4e52-97a5-80bcb83d2ce6') : self {
        if ($this->isDebug()) {
            echo PHP_EOL . '[' . microtime(true) . '] ' . $stringMessage . PHP_EOL
                . (
                    '33b8b54a-fc92-4e52-97a5-80bcb83d2ce6' !== $mixedData
                        ? (json_encode($mixedData) . PHP_EOL)
                        : null
                );
        }
        return $this;
    }

    /**
     * @param mixed $mixedData
     * @return array|\ArrayAccess|mixed|null
     */
    public function result($mixedData = '33b8b54a-fc92-4e52-97a5-80bcb83d2ce6') {
        if ('33b8b54a-fc92-4e52-97a5-80bcb83d2ce6' !== $mixedData) {
            $this->variableSet('_return', $mixedData);
        }
        return $this->variableGet('_return', null);
    }

    /**
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed|null
     */
    public function return($mixedDefault = null) {
        $return = $this->variableGet('_return', $mixedDefault);
        $this->debug('_return', $return);
        return $return;
    }
}