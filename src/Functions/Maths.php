<?php namespace Mossengine\FiveCode\Functions;

use Mossengine\Helper;

/**
 * Class Maths
 * @package Mossengine\FiveCode\Parsers
 */
class Maths extends FunctionsAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'maths.addition' => function($fiveCode, $mixedData) { return self::addition($mixedData); },
            'maths.subtract' => function($fiveCode, $mixedData) { return self::subtract($mixedData); },
            'maths.divide' => function($fiveCode, $mixedData) { return self::divide($mixedData); },
            'maths.multiply' => function($fiveCode, $mixedData) { return self::multiply($mixedData); },
            'maths.random' => function($fiveCode, $mixedData) { return self::random($mixedData); },
        ];
    }

    /**
     * @param array $mixedData
     * @return float|int
     */
    public static function addition(array $mixedData = []) {
        $value = array_shift($mixedData);
        foreach ($mixedData as $arg) {
            $value += floatval($arg);
        }
        return $value;
    }

    /**
     * @param array $mixedData
     * @return float|int
     */
    public static function subtract(array $mixedData = []) {
        $value = array_shift($mixedData);
        foreach ($mixedData as $arg) {
            $value -= floatval($arg);
        }
        return $value;
    }

    /**
     * @param array $mixedData
     * @return float|int
     */
    public static function divide(array $mixedData = []) {
        $value = array_shift($mixedData);
        foreach ($mixedData as $arg) {
            $value /= floatval($arg);
        }
        return $value;
    }

    /**
     * @param array $mixedData
     * @return float|int
     */
    public static function multiply(array $mixedData = []) {
        $value = array_shift($mixedData);
        foreach ($mixedData as $arg) {
            $value *= floatval($arg);
        }
        return $value;
    }

    /**
     * @param array $mixedData
     * @return int
     */
    public static function random(array $mixedData = []) {
        return mt_rand(
            Helper::Array()->Get($mixedData, 0, 0),
            Helper::Array()->Get($mixedData, 1, 1)
        );
    }

}