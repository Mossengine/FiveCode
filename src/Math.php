<?php

namespace Mossengine\FiveCode;

/**
 * Class Math
 * @package Mossengine\FiveCode
 */
class Math
{
    use ArrTrait;

    public static $arrayFiveCodeFunctions = [
        'mossengine.fivecode.math.addition' => '\Mossengine\FiveCode\Math::addition',
        'mossengine.fivecode.math.subtract' => '\Mossengine\FiveCode\Math::subtract',
        'mossengine.fivecode.math.divide' => '\Mossengine\FiveCode\Math::divide',
        'mossengine.fivecode.math.multiply' => '\Mossengine\FiveCode\Math::multiply',
        'mossengine.fivecode.math.random' => '\Mossengine\FiveCode\Math::random',
    ];

    /**
     * @return float|int
     */
    public static function addition() {
        $arrayParameters = func_get_args();
        $value = static::array_pull($arrayParameters, 0, 0);
        foreach ($arrayParameters as $arg) {
            $value += floatval($arg);
        }
        return $value;
    }

    /**
     * @return float|int
     */
    public static function subtract() {
        $arrayParameters = func_get_args();
        $value = static::array_pull($arrayParameters, 0, 0);
        foreach ($arrayParameters as $arg) {
            $value -= floatval($arg);
        }
        return $value;
    }

    /**
     * @return float|int
     */
    public static function divide() {
        $arrayParameters = func_get_args();
        $value = static::array_pull($arrayParameters, 0, 0);
        foreach ($arrayParameters as $arg) {
            $value /= floatval($arg);
        }
        return $value;
    }

    /**
     * @return float|int
     */
    public static function multiply() {
        $arrayParameters = func_get_args();
        $value = static::array_pull($arrayParameters, 0, 0);
        foreach ($arrayParameters as $arg) {
            $value *= floatval($arg);
        }
        return $value;
    }

    /**
     * @return int
     */
    public static function random() {
        $arrayParameters = func_get_args();
        return mt_rand(static::array_get($arrayParameters, 0, 0), static::array_get($arrayParameters, 1, 100));
    }
}