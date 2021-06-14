<?php namespace Mossengine\FiveCode\Parsers;

/**
 * Class Instructions
 * @package Mossengine\FiveCode\Parsers
 */
class Instructions extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'instruction' => function($fiveCode, $arrayData) { return $fiveCode->parse([$arrayData]); },
            'instructions' => function($fiveCode, $arrayData) { return $fiveCode->parse($arrayData); },
        ];
    }

}