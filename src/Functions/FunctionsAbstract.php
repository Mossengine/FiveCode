<?php namespace Mossengine\FiveCode\Functions;

/**
 * Class FunctionsAbstract
 * @package Mossengine\FiveCode\Parsers
 */
abstract class FunctionsAbstract {

    /**
     * @return array
     */
    public abstract static function register() : array;

    /**
     * @return array
     */
    public static function settings() : array {
        return [];
    }

}
