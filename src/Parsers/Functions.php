<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\Exceptions\InstructionException;
use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Functions
 * @package Mossengine\FiveCode\Parsers
 */
class Functions extends ModuleAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'function' => function($fiveCode, $arrayData) { return self::parse($fiveCode, [$arrayData]); },
            'functions' => function($fiveCode, $arrayData) { return self::parse($fiveCode, $arrayData); },
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayFunctions
     * @return array|\ArrayAccess|mixed|null
     * @throws InstructionException
     */
    public static function parse(FiveCode $fiveCode, array $arrayFunctions = []) {
        foreach ($arrayFunctions as $arrayFunction) {
            $stringFunctionType = ___::arrayFirstKey($arrayFunction);
            $mixedFunctionData = ___::arrayGet($arrayFunction, $stringFunctionType, null);
            $mixedFunctionName = ___::arrayGet($mixedFunctionData, 'key', null);
            $mixedFunctionCallable = ___::arrayGet($mixedFunctionData, 'callable', $mixedFunctionData);

            if (is_null($mixedFunctionName)) {
                $mixedFunctionName = $stringFunctionType;
                $stringFunctionType = 'set';
            }

            switch ($stringFunctionType) {
                case 'set':
                    $fiveCode->functionSet($mixedFunctionName, $mixedFunctionCallable);
                    break;
                case 'forget':
                    $fiveCode->functionForget($mixedFunctionName);
                    break;
                default:
                    throw new InstructionException('Invalid function : ' . $stringFunctionType);
            }
        }

        return $fiveCode->variableGet('return');
    }

}