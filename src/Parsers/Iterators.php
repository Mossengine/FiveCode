<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Iterators
 * @package Mossengine\FiveCode\Parsers
 */
class Iterators extends ParsersAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'for' => function($fiveCode, $arrayData) { return self::for($fiveCode, $arrayData); },
            'each' => function($fiveCode, $arrayData) { return self::each($fiveCode, $arrayData); },
            'do' => function($fiveCode, $arrayData) { return self::do($fiveCode, $arrayData); },
            'while' => function($fiveCode, $arrayData) { return self::while($fiveCode, $arrayData); }
        ];
    }

    /**
     * @return array|string
     */
    public static function settings() : array {
        return [
            '_iterators' => [
                'for' => [
                    'max' => [
                        'iteration' => 10,
                        'duration' => 10
                    ]
                ],
                'each' => [
                    'max' => [
                        'iteration' => 10,
                        'duration' => 10
                    ]
                ],
                'do' => [
                    'max' => [
                        'iteration' => 10,
                        'duration' => 10
                    ]
                ],
                'while' => [
                    'max' => [
                        'iteration' => 10,
                        'duration' => 10
                    ]
                ]
            ]
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|false|mixed|null
     * @throws InstructionException
     */
    public static function for(FiveCode $fiveCode, array $arrayArguments = []) {
        $mixedResult = $fiveCode->result();

        // Get the arguments
        $arrayForArguments = array_map(
            function ($arrayArgument) use ($fiveCode) {
                return $fiveCode->instructions($arrayArgument);
            },
            (array) ___::arrayGet($arrayArguments, 0, [])
        );

        $intStart = 0;
        $intLimit = 0;
        $intStep = 1;

        // support more than one argument, middle argument is the operator ( type )
        switch (count($arrayForArguments)) {
            case 1:
                $intLimit = (int) $arrayForArguments[0];
                break;
            case 2:
                $intStart = (int) $arrayForArguments[0];
                $intLimit = (int) $arrayForArguments[1];
                break;
            case 3:
                $intStart = (int) $arrayForArguments[0];
                $intLimit = (int) $arrayForArguments[1];
                $intStep = (int) $arrayForArguments[2];
                break;
        }

        for (
            $i = $intStart;
            $i <= $intLimit;
            $i += (
                ($intLimit >= $intStart)
                    ? $intStep
                    : $intStep * -1
            )
        ) {
            $fiveCode->loopUp('for');
            $fiveCode->variableSet('_iterator.for.index', $i);
            if (
                $fiveCode->isLoopUnder(
                    'for',
                    $fiveCode->settingGet('_iterators.for.max.iteration')
                )
            ) {
                $mixedResult = $fiveCode->instructions(
                    ___::arrayGet($arrayArguments, 1, null)
                );
            }
        }
        $fiveCode->loopSet('for', 0);

        $fiveCode->variableForget('_iterator.for.index');

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|false|mixed|null
     * @throws InstructionException
     */
    public static function each(FiveCode $fiveCode, array $arrayArguments = []) {
        $mixedResult = $fiveCode->result();

        foreach (
            (array) $fiveCode->instructions(
                ___::arrayGet($arrayArguments, 0, [])
            )
            as $index => $item
        ) {
            $fiveCode->loopUp('each');
            $fiveCode->variableSet('_iterator.each.index', $index);
            $fiveCode->variableSet('_iterator.each.item', $item);
            if (
                $fiveCode->isLoopUnder(
                    'each',
                    $fiveCode->settingGet('_iterators.each.max.iteration')
                )
            ) {
                $mixedResult = $fiveCode->instructions(
                    ___::arrayGet($arrayArguments, 1, null)
                );
            }
        }
        $fiveCode->loopSet('each', 0);

        $fiveCode->variableForget('_iterator.each.index');
        $fiveCode->variableForget('_iterator.each.item');

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return false|mixed|null
     * @throws InstructionException
     */
    public static function do(FiveCode $fiveCode, array $arrayArguments = []) {
        do {
            $fiveCode->loopUp('do');
            $fiveCode->variableSet('_iterator.do.iteration', $fiveCode->loopGet('do'));
            $mixedResult = $fiveCode->instructions(
                ___::arrayGet($arrayArguments, 0, null)
            );
        } while (
            $fiveCode->isLoopUnder(
                'do',
                $fiveCode->settingGet('_iterators.do.max.iteration')
            )
            && $fiveCode->instructions(
                ___::arrayGet($arrayArguments, 1, false)
            )
        );

        $fiveCode->loopSet('do', 0);
        $fiveCode->variableForget('_iterator.do.iteration');

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return array|\ArrayAccess|mixed|null
     * @throws InstructionException
     */
    public static function while(FiveCode $fiveCode, array $arrayArguments = []) {
        while (
            $fiveCode->isLoopUnder(
                'while',
                $fiveCode->settingGet('_iterators.while.max.iteration')
            )
            && $fiveCode->instructions(
                ___::arrayGet($arrayArguments, 0, false)
            )
        ) {
            $fiveCode->loopUp('while');
            $fiveCode->variableSet('_iterator.while.iteration', $fiveCode->loopGet('while'));
            $mixedResult = $fiveCode->instructions(
                ___::arrayGet($arrayArguments, 1, null)
            );
        };

        $fiveCode->loopSet('while', 0);
        $fiveCode->variableForget('_iterator.while.iteration');

        return $fiveCode->result($mixedResult);
    }

}