<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Values
 * @package Mossengine\FiveCode\Parsers
 */
class Values extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'value' => function($fiveCode, $arrayData) { return self::value($fiveCode, $arrayData); },
            'array' => function($fiveCode, $arrayData) { return self::array($fiveCode, $arrayData); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param null $mixedData
     * @return array|\ArrayAccess|mixed|null
     */
    public static function value(FiveCode $fiveCode, $mixedData = null) {
        return $fiveCode->result($mixedData);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $mixedData
     * @return array|\ArrayAccess|mixed|null
     * @throws InstructionException
     */
    public static function array(FiveCode $fiveCode, array $mixedData = []) {
        return $fiveCode->result(array_map(
            function($mixedItem) use ($fiveCode) {
                return $fiveCode->instructions($mixedItem);
            },
            $mixedData
        ));
    }

}