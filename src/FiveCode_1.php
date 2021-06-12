<?php

namespace Mossengine\FiveCode;

/**
 * Class FiveCode
 * @package Mossengine\FiveCode
 */
class FiveCode1
{
    use ArrTrait;

    /**
     * Internal variable used to store the execution variables
     *
     * @var array
     */
    private $arrayVariables = [];

    /**
     * Internal array used to store the supported functions for execution, this gets populated as part of the constructor
     *
     * @var array
     */
    private $arraySupportedFiveCodeFunctions = [];

    /**
     * FiveCode constructor.
     * @param array $arrayParameters
     */
    public function __construct($arrayParameters = []) {
        $this->arraySupportedFiveCodeFunctions = array_merge($this->arraySupportedFiveCodeFunctions, static::array_get($arrayParameters, 'functions', []));
    }

    /**
     * Execute the FiveCode from a JSON string
     *
     * @param string $stringFiveCode
     */
    public function executeJson($stringFiveCode = '[]') {
        // decode the FiveCode string
        $this->execute(json_decode($stringFiveCode, true));
    }

    /**
     * Executes the FiveCode for a provided array of variables and/or instructions
     *
     * @param array $arrayFiveCode
     */
    public function execute(array $arrayFiveCode = []) {
        // Get the variables from the FiveCode
        $this->arrayVariables = $this->array_get($arrayFiveCode, 'variables', []);

        // Execute the first layer of instructions from the FiveCode
        $this->instructions($this->array_get($arrayFiveCode, 'instructions', []));
    }

    /**
     * This function gets called when instructions are found at a given level within the FiveCode executed process
     * FiveCode can have nested instructions so this can loop down many times depending on the FiveCode structure
     *
     * @param array $arrayInstructions
     */
    private function instructions(array $arrayInstructions = []) {
        // Process each instruction
        foreach ($arrayInstructions as $arrayInstruction) {
            switch ($this->array_get($arrayInstruction, 'type', null)) {
                case 'instructions':
                    $this->instructions($this->array_get($arrayInstruction, 'instructions', []));
                    break;
                case 'variables':
                    $this->variables($this->array_get($arrayInstruction, 'variables', []));
                    break;
                case 'functions':
                    $this->functions($this->array_get($arrayInstruction, 'functions', []));
                    break;
                case 'conditions':
                    if (
                        true === $this->array_get(
                            $this->conditions(
                                $this->array_get(
                                    $arrayInstruction,
                                    'conditions',
                                    []
                                )
                            ),
                            $this->array_get(
                                $arrayInstruction,
                                'validation',
                                'all'
                            )
                        )
                        && $this->array_has($arrayInstruction, 'instructions')
                    ) {
                        $this->instructions($this->array_get($arrayInstruction, 'instructions', []));
                    }
                    break;
                case 'iterators':
                    $this->iterators($this->array_get($arrayInstruction, 'iterators', []));
                    break;
            }
        }
    }

    /**
     * This function is used to get, set or forget a variable from within the stored execution variables
     *
     * @param null $name
     * @param string $value
     * @return mixed
     */
    public function variable($name = null, $value = 'SuperCatMonkeyHotDog') {
        if ('SuperCatMonkeyHotDog' !== $value) {
            if (!is_null($value)) {
                $this->array_set($this->arrayVariables, $name, $value);
            } else {
                $this->array_forget($this->arrayVariables, $name);
            }
        }
        return $this->array_get($this->arrayVariables, $name, null);
    }

    /**
     * This function sets a variable value into the variables array either based on a specific value defined or the reference to another stored variable
     *
     * @param array $arrayVariables
     */
    private function variables(array $arrayVariables = []) {
        foreach ($arrayVariables as $arrayVariable) {
            switch ($this->array_get($arrayVariable, 'type', null)) {
                case 'variable':
                    $this->variable($this->array_get($arrayVariable, 'variable', 'default'), $this->variable($this->array_get($arrayVariable, 'variable', 'default')));
                    break;
                case 'value':
                    $this->variable($this->array_get($arrayVariable, 'variable', 'default'), $this->array_get($arrayVariable, 'value', null));
                    break;
            }
        }
    }

    /**
     * This function is where the instructions within the FiveCode calls on one or more functions to be executed, from
     * here it will execute the instructed function with the suggested variables either based on value or reference into
     * a function that is part of the supported functions array. The results of the function can then be piped out to a variable or nothing to be done... more options to come.
     *
     * @param array $arrayFunctions
     */
    private function functions(array $arrayFunctions = []) {
        foreach ($arrayFunctions as $arrayFunction) {
            if ($this->array_has($arrayFunction, 'parameters')) {
                $arrayParameters = array_map(
                    function ($arrayParameter) {
                        switch ($this->array_get($arrayParameter, 'type', null)) {
                            case 'variable':
                                return $this->variable($this->array_get($arrayParameter, 'variable', 'default'));
                                break;
                            case 'value':
                                return $this->array_get($arrayParameter, 'value', null);
                                break;
                        }

                        return null;
                    },
                    $this->array_get($arrayFunction, 'parameters', [])
                );
            } else {
                $arrayParameters = [];
            }

            $result = null;

            if (in_array($this->array_get($arrayFunction, 'type', null), array_keys($this->arraySupportedFiveCodeFunctions))) {
                $result = call_user_func_array($this->array_get($this->arraySupportedFiveCodeFunctions, $this->array_get($arrayFunction, 'type', null), null), $arrayParameters);
            }

            if (!empty($result)) {
                foreach ($this->array_get($arrayFunction, 'returns', []) as $arrayReturn) {
                    switch ($this->array_get($arrayReturn, 'type', null)) {
                        case 'variable':
                            $this->variable($this->array_get($arrayReturn, 'variable', 'default'), $result);
                            break;
                    }
                }
            }
        }
    }

    /**
     * This function gets called when the FiveCode instructions require some conditions to be met first before executing more instructions.
     * This supports basic comparitions between two variables and/or values either referenced or defined within the instructions data.
     *
     * @param array $arrayConditions
     * @return array
     */
    private function conditions(array $arrayConditions = []) {
        $boolAll = true;
        $boolAny = false;
        foreach ($arrayConditions as $arrayCondition) {
            switch ($this->array_get($arrayCondition, 'type', null)) {
                case 'compare':
                    switch ($this->array_get($arrayCondition, 'left.type', null)) {
                        case 'variable':
                            $mixedLeft = $this->variable($this->array_get($arrayCondition, 'left.variable', 'default'));
                            break;
                        case 'value':
                            $mixedLeft = $this->array_get($arrayCondition, 'left.value', 'BlueBatBerryWalk' . microtime(true)); // random weird default to prevent accidental matching
                            break;
                    }
                    switch ($this->array_get($arrayCondition, 'right.type', null)) {
                        case 'variable':
                            $mixedRight = $this->variable($this->array_get($arrayCondition, 'right.variable', 'default'));
                            break;
                        case 'value':
                            $mixedRight = $this->array_get($arrayCondition, 'right.value', 'SandMouseCherryWheel' . microtime(true)); // random weird default to prevent accidental matching
                            break;
                    }
                    switch ($this->array_get($arrayCondition, 'operator', null)) {
                        case 'lt':
                        case '<':
                            if ($mixedLeft < $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                        case 'lte':
                        case '<=':
                            if ($mixedLeft <= $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                        case 'eq':
                        case '==':
                            if ($mixedLeft == $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                        case 'neq':
                        case '!=':
                            if ($mixedLeft != $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                        case 'gt':
                        case '>':
                            if ($mixedLeft > $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                        case 'gte':
                        case '>=':
                            if ($mixedLeft >= $mixedRight) {
                                $boolAny = true;
                            } else {
                                $boolAll = false;
                            }
                            break;
                    }
                    break;
            }
        }
        return ['all' => $boolAll, 'any' => $boolAny];
    }

    /**
     * This function gets called when the instructions call on an iterator of either for or each where classic iterator
     * behaviour takes place. You can for loop by setting the start number, its limit and stepping or provide an array
     * of things to iterate over for further execution of instructions from within the iterator loop. While iterating
     * you can reference the index and value ( if using each ) from the normal variables design and if you need to nest
     * multiple iterators and retain their index and/or value then you can also define an identifier that isolates the
     * index and value away from accidental override from subsequent iterators.
     *
     * @param array $arrayIterators
     */
    private function iterators(array $arrayIterators = []) {
        foreach ($arrayIterators as $arrayIterator) {
            $stringIdentifier = $this->array_get($arrayIterator, 'identifier', null);
            switch ($this->array_get($arrayIterator, 'type', null)) {
                case 'for':
                    for (
                        $i = $this->array_get($arrayIterator, 'start', 1);
                        $i <= $this->array_get($arrayIterator, 'limit', 10);
                        $i += $this->array_get($arrayIterator, 'step', 1)
                    ) {
                        $this->variable('iterate.' . (!empty($stringIdentifier) ? $stringIdentifier . '.' : '') . 'index', $i);
                        $this->instructions($this->array_get($arrayIterator, 'instructions', []));
                    }
                    break;
                case 'each':
                    $arrayToIterate = [];
                    switch ($this->array_get($arrayIterator, 'each', null)) {
                        case 'variable':
                            $arrayToIterate = $this->variable($this->array_get($arrayIterator, 'variable', 'default'));
                            break;
                        case 'value':
                            $arrayToIterate = $this->array_get($arrayIterator, 'value', []);
                            break;
                    }

                    if (is_array($arrayToIterate)) {
                        foreach ($arrayToIterate as $mixedIterateKey => $mixedIterateValue) {
                            $this->variable('iterate.' . (!empty($stringIdentifier) ? $stringIdentifier . '.' : '') . 'index', $mixedIterateKey);
                            $this->variable('iterate.' . (!empty($stringIdentifier) ? $stringIdentifier . '.' : '') . 'value', $mixedIterateValue);
                            $this->instructions($this->array_get($arrayIterator, 'instructions', []));
                        }
                    }
                    break;
            }
            $this->variable('iterate.' . (!empty($stringIdentifier) ? $stringIdentifier . '.' : '') . 'index', null);
            $this->variable('iterate.' . (!empty($stringIdentifier) ? $stringIdentifier . '.' : '') . 'value', null);
        }
    }
}