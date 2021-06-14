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

}
