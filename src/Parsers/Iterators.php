<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Iterators
 * @package Mossengine\FiveCode\Parsers
 */
class Iterators extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'iterator' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'iterators' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayIterators
     * @return false|mixed|null
     */
    public static function parse(FiveCode $fiveCode, array $arrayIterators = []) {
        $mixedResult = null;

        foreach ($arrayIterators as $arrayIterator) {
            // for | each
            $stringIteratorType = ___::arrayFirstKey($arrayIterator);

            // Iterator data
            $mixedIteratorData = ___::arrayGet($arrayIterator, $stringIteratorType, []);

            // Iterator Id
            $stringIteratorId = ___::arrayGet($mixedIteratorData, 'id', null);

            // What type are we executing
            if (in_array($stringIteratorType, ['for', 'each'])) {
                $mixedResult = call_user_func_array(
                    [self::class, $stringIteratorType],
                    [$fiveCode, $stringIteratorId, $mixedIteratorData]
                );
            }

            // Forget tracking keys
            $fiveCode->variableForget(
                'iterate.' . (
                    !is_null($stringIteratorId)
                        ? $stringIteratorId . '.'
                        : ''
                ) . 'index'
            );
            // Forget tracking values
            $fiveCode->variableForget(
                'iterate.' . (
                    !is_null($stringIteratorId)
                        ? $stringIteratorId . '.'
                        : ''
                ) . 'value'
            );
        }

        $fiveCode->variableSet('return', $mixedResult);
        return $mixedResult;
    }

    /**
     * @param FiveCode $fiveCode
     * @param $stringIteratorId
     * @param $mixedIteratorData
     * @return false|mixed|null
     * @throws InstructionException
     */
    private static function for(FiveCode $fiveCode, $stringIteratorId, $mixedIteratorData) {
        $mixedResult = null;

        for (
            $i = ___::arrayGet($mixedIteratorData, 'start', 0);
            $i <= ___::arrayGet($mixedIteratorData, 'limit', 0);
            $i += ___::arrayGet($mixedIteratorData, 'step', 1)
        ) {
            $mixedResult = self::work($fiveCode, $stringIteratorId, $i, null, $mixedIteratorData);
        }

        return $mixedResult;
    }

    /**
     * @param FiveCode $fiveCode
     * @param $stringIteratorId
     * @param $mixedIteratorData
     * @return false|mixed|null
     * @throws InstructionException
     */
    private static function each(FiveCode $fiveCode, $stringIteratorId, $mixedIteratorData) {
        $mixedResult = null;

        foreach (
            is_array(
                $arrayData = (
                    !is_null(
                        $stringVariable = ___::arrayGet(
                            $mixedIteratorData,
                            'variable',
                            null
                        )
                    )
                    && is_string($stringVariable)
                        ? (
                            $fiveCode->isVariableAllowed($stringVariable, 'get')
                                ? $fiveCode->variableGet($stringVariable, null)
                                : []
                        )
                        : (
                            ___::arrayGet(
                                $mixedIteratorData,
                                'value',
                                []
                            )
                        )
                )
            )
                ? $arrayData
                : []
            as $mixedIterateKey => $mixedIterateValue
        ) {
            $mixedResult = self::work($fiveCode, $stringIteratorId, $mixedIterateKey, $mixedIterateValue, $mixedIteratorData);
        }

        return $mixedResult;
    }

    /**
     * @param FiveCode $fiveCode
     * @param $stringIteratorId
     * @param $index
     * @param $value
     * @param $mixedIteratorData
     * @return false|mixed|null
     * @throws InstructionException
     */
    private static function work(FiveCode $fiveCode, $stringIteratorId, $index, $value, $mixedIteratorData) {
        $fiveCode->variableSet(
            'iterate.' . (
                !is_null($stringIteratorId)
                    ? $stringIteratorId . '.'
                    : ''
            ) . 'index',
            $index
        );
        $fiveCode->variableSet(
            'iterate.' . (
                !is_null($stringIteratorId)
                    ? $stringIteratorId . '.'
                    : ''
            ) . 'value',
            $value
        );
        return $fiveCode->parse(
            ___::arrayGet(
                $mixedIteratorData,
                'instructions',
                [
                    ___::arrayGet(
                        $mixedIteratorData,
                        'instruction',
                        null
                    )
                ]
            )
        );
    }

}