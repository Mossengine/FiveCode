<?php namespace Mossengine\FiveCode\Parsers;

/**
 * Class ParsersAbstract
 * @package Mossengine\FiveCode\Parsers
 */
abstract class ParsersAbstract {

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
