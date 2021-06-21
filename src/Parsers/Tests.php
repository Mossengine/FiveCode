<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\FiveCode;

/**
 * Class Tests
 * @package Mossengine\FiveCode\Parsers
 */
class Tests extends ParsersAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'test1' => function($fiveCode, $arrayData) { return self::test1($fiveCode, $arrayData); },
            'test2' => function($fiveCode, $arrayData) { return self::test2($fiveCode, $arrayData); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param null $mixedData
     * @return array|\ArrayAccess|mixed|null
     */
    public static function test1(FiveCode $fiveCode, $mixedData = null) {
        return $fiveCode->result($mixedData . ' : ' . $mixedData);
    }

    /**
     * @param FiveCode $fiveCode
     * @param null $mixedData
     * @return array|\ArrayAccess|mixed|null
     */
    public static function test2(FiveCode $fiveCode, $mixedData = null) {
        return $fiveCode->result(substr($mixedData, 1));
    }

}