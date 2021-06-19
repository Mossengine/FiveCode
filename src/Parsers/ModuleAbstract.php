<?php namespace Mossengine\FiveCode\Parsers;
/**
 * Class ModuleAbstract
 * @package Mossengine\FiveCode\Parsers
 */
abstract class ModuleAbstract {

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
