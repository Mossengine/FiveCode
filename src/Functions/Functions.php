<?php namespace Mossengine\FiveCode\Functions;

/**
 * Class Functions
 * @package Mossengine\FiveCode\Functions
 */
class Functions extends FunctionsAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'functions.one' => function($fiveCode, $mixedData) { return self::one($mixedData); }
        ];
    }

    /**
     * @param array $mixedData
     * @return float|int
     */
    public static function one(array $mixedData = []) {
        $value = array_shift($mixedData);
        foreach ($mixedData as $arg) {
            $value += floatval($arg);
        }
        return $value;
    }

}