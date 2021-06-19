<?php namespace Mossengine\FiveCode\Helpers;

use Illuminate\Support\Arr;

/**
 * Class ___
 * @package Mossengine\Helpers
 */
class ___ {

    /**
     * @param array $array
     * @return bool
     */
    public static function arrayIsAssociative(array $array) : bool {
        return (
            !([] === $array)
            && array_keys($array) !== range(0, count($array) - 1)
        );
    }

    /**
     * @param array $array
     * @param $stringPath
     * @return bool
     */
    public static function arrayHas(array $array, $stringPath) : bool {
        return Arr::has($array, $stringPath);
    }

    /**
     * @param $array
     * @param $stringPath
     * @param null $mixedDefault
     * @return array|\ArrayAccess|mixed
     */
    public static function arrayGet($array, $stringPath, $mixedDefault = null) {
        return (
            !is_array($array)
            || !Arr::has($array, $stringPath)
                ? $mixedDefault
                : Arr::get($array, $stringPath, $mixedDefault)
        );
    }

    /**
     * @param array $array
     * @param $stringPath
     * @param $mixedValue
     * @return array
     */
    public static function arraySet(array &$array, $stringPath, $mixedValue) : array {
        return Arr::set($array, $stringPath, $mixedValue);
    }

    /**
     * @param array $array
     * @param $stringPath
     */
    public static function arrayForget(array &$array, $stringPath) {
        Arr::forget($array, $stringPath);
    }

    /**
     * @param array $array
     * @param callable|null $callable
     * @return bool
     */
    public static function arrayEvery(array $array, callable $callable = null) : bool {
        foreach ($array as $key => $item) {
            if (
                (
                    is_callable($callable)
                    && !call_user_func($callable, $item, $key)
                )
                || (
                    !is_callable($callable)
                    && !$item
                )
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $array
     * @param callable $callable
     * @return bool
     */
    public static function arraySome(array $array, callable $callable) : bool {
        foreach ($array as $key => $item) {
            if (
                (
                    is_callable($callable)
                    && call_user_func($callable, $item, $key)
                )
                || (
                    !is_callable($callable)
                    && $item
                )
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $array
     * @param null $mixedDefault
     * @return false|mixed|null
     */
    public static function arrayFirstKey(array $array, $mixedDefault = null) {
        $keys = array_keys($array);
        return empty($array) ? $mixedDefault : reset($keys);
    }

    /**
     * @param array $array
     * @param null $mixedDefault
     * @return false|mixed|null
     */
    public static function arrayFirstValue(array $array, $mixedDefault = null) {
        return empty($array) ? $mixedDefault : reset($array);
    }

    /**
     * @param $mixedLeft
     * @param string $operator
     * @param $mixedRight
     * @return bool
     */
    public static function is($mixedLeft, string $operator, $mixedRight) : bool {
        return call_user_func_array(
            [
                self::class,
                self::arrayGet(
                    [
                        '==' => 'isLike',
                        '===' => 'isSame',
                        '!=' => 'isNotLike',
                        '!==' => 'isNotSame',
                        '>' => 'isMore',
                        '>=' => 'isMoreOrSame',
                        '<' => 'isLess',
                        '<=' => 'isLessOrSame'
                    ],
                    $operator,
                    'isAlwaysFalse'
                )
            ],
            [$mixedLeft, $mixedRight]
        );
    }

    /**
     * @return false
     */
    public static function isAlwaysFalse() : bool {
        return false;
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isLike($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft == $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isSame($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft === $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isNotLike($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft != $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isNotSame($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft !== $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isMore($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft > $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isMoreOrSame($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft >= $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isLess($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft < $mixedRight);
    }

    /**
     * @param $mixedLeft
     * @param $mixedRight
     * @return bool
     */
    public static function isLessOrSame($mixedLeft, $mixedRight) : bool {
        return ($mixedLeft <= $mixedRight);
    }

}