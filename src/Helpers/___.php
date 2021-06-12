<?php namespace Mossengine\FiveCode\Helpers;

use Illuminate\Support\Arr;

/**
 * Class ___
 * @package Mossengine\Helpers
 */
class ___ {

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

}