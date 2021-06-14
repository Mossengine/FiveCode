<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\FiveCode;

/**
 * Class Values
 * @package Mossengine\FiveCode\Parsers
 */
class Values extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'value' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'values' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayValues
     * @return mixed|null
     */
    public static function parse(FiveCode $fiveCode, array $arrayValues = []) {
        $mixedResult = null;

        foreach ($arrayValues as $mixedValue) {
            $fiveCode->variableSet('return', ($mixedResult = $mixedValue));
        }

        return $mixedResult;
    }

}