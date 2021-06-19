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
            'set' => function($fiveCode, $arrayData) { return self::set($fiveCode, $arrayData); },
            'get' => function($fiveCode, $arrayData) { return self::get($fiveCode, $arrayData); },
            'forget' => function($fiveCode, $arrayData) { return self::forget($fiveCode, $arrayData); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $mixedData
     * @return array|\ArrayAccess|mixed|null
     * @throws InstructionException
     */
    public static function set(FiveCode $fiveCode, array $mixedData = []) {
        if (
            !empty($mixedData)
            && is_string($stringKey = ___::arrayGet($mixedData, 0, null))
            && $fiveCode->isVariableAllowed($stringKey, 'set')
        ) {
            $fiveCode->variableSet(
                $stringKey,
                $fiveCode->instructions(___::arrayGet($mixedData, 1, null))
            );
        }

        return $fiveCode->result();
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $mixedData
     * @return array|\ArrayAccess|mixed|null
     * @throws InstructionException
     */
    public static function get(FiveCode $fiveCode, array $mixedData = []) {
        $mixedResult = $fiveCode->result();
        if (
            !empty($mixedData)
            && is_string($stringKey = ___::arrayGet($mixedData, 0, null))
            && $fiveCode->isVariableAllowed($stringKey, 'get')
        ) {
            $mixedResult = $fiveCode->variableGet(
                $stringKey,
                $fiveCode->instructions(___::arrayGet($mixedData, 1, null))
            );
        }

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $mixedData
     * @return array|\ArrayAccess|mixed|null
     */
    public static function forget(FiveCode $fiveCode, array $mixedData = []) {
        if (
            !empty($mixedData)
            && is_string($stringKey = ___::arrayGet($mixedData, 0, null))
            && $fiveCode->isVariableAllowed($stringKey, 'forget')
        ) {
            $fiveCode->variableForget($stringKey);
        }
        return $fiveCode->result();
    }

}