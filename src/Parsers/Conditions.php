<?php namespace Mossengine\FiveCode\Parsers;

use Mossengine\FiveCode\FiveCode;
use Mossengine\FiveCode\Helpers\___;

/**
 * Class Conditions
 * @package Mossengine\FiveCode\Parsers
 */
class Conditions extends ParsersAbstract {

    /**
     * @return array|string
     */
    public static function register() : array {
        return [
            'if' => function($fiveCode, $arrayData) { return self::if($fiveCode, $arrayData); },

            'all' => function($fiveCode, $arrayData) { return self::all($fiveCode, $arrayData); },
            'any' => function($fiveCode, $arrayData) { return self::any($fiveCode, $arrayData); },

            '==' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '=='); },
            '===' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '==='); },
            '!=' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '!='); },
            '!==' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '!=='); },
            '>' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '>'); },
            '>=' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '>='); },
            '<' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '<'); },
            '<=' => function($fiveCode, $arrayData) { return self::statement($fiveCode, $arrayData, '<='); }
        ];
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayArguments
     * @return bool|mixed|null
     */
    public static function if(FiveCode $fiveCode, array $arrayArguments = []) {
        // Run the first argument to get the conditional results ( true | false )
        $mixedResult = $fiveCode->instructions(
            ___::arrayGet($arrayArguments, 0, false)
        );

        if (
            // If true, execute second argument if also not null ( use ["value" => null] if you want actual null )
            (
                true === $mixedResult
                && !is_null(
                    $mixedTrueInstructionsOrInstruction = ___::arrayGet(
                        $arrayArguments,
                        1,
                        null
                    )
                )
            )

            // If false, execute third argument if also not null ( use ["value" => null] if you want actual null )
            || (
                true !== $mixedResult
                && !is_null(
                    $mixedTrueInstructionsOrInstruction = ___::arrayGet(
                        $arrayArguments,
                        2,
                        null
                    )
                )
            )
        ) {
            $mixedResult = $fiveCode->instructions($mixedTrueInstructionsOrInstruction);
        }

        // if a fourth agument exists then execute as this is the always argument
        if (
            !is_null(
                $mixedTrueInstructionsOrInstruction = ___::arrayGet(
                    $arrayArguments,
                    3,
                    null
                )
            )
        ) {
            $mixedResult = $fiveCode->instructions($mixedTrueInstructionsOrInstruction);
        }

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $mixedStatementsOrStatement
     * @return array|\ArrayAccess|false|mixed|null
     */
    public static function all(FiveCode $fiveCode, array $mixedStatementsOrStatement = []) {
        $mixedResult = $fiveCode->result();

        // Loop over the statements
        foreach (
            (
                !is_array($mixedStatementsOrStatement)
                || ___::arrayIsAssociative($mixedStatementsOrStatement)
                    ? [$mixedStatementsOrStatement]
                    : $mixedStatementsOrStatement
            )
            as $arrayStatement
        ) {
            // Call the statement
            if (
                false === (
                    $mixedResult = $fiveCode->instructions($arrayStatement)
                )
            ) {
                break;
            }
        }

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param mixed $mixedStatementsOrStatement
     * @return array|\ArrayAccess|false|mixed|null
     */
    public static function any(FiveCode $fiveCode, $mixedStatementsOrStatement = []) {
        $mixedResult = $fiveCode->result();

        // Loop over the statements
        foreach (
            (
                !is_array($mixedStatementsOrStatement)
                || ___::arrayIsAssociative($mixedStatementsOrStatement)
                    ? [$mixedStatementsOrStatement]
                    : $mixedStatementsOrStatement
            )
            as $arrayStatement
        ) {
            // Call the statement
            if (
                false !== (
                    $mixedResult = $fiveCode->instructions($arrayStatement)
                )
            ) {
                break;
            }
        }

        return $fiveCode->result($mixedResult);
    }

    /**
     * @param FiveCode $fiveCode
     * @param array $arrayData
     * @param string $stringStatementType
     * @return bool
     */
    public static function statement(FiveCode $fiveCode, array $arrayData, string $stringStatementType) : bool {
        // Get the arguments
        $arrayArguments = array_map(
            function ($arrayArgument) use ($fiveCode) {
                return $fiveCode->instructions($arrayArgument);
            },
            $arrayData
        );

        // support more than one argument, middle argument is the operator ( type )
        switch (count($arrayArguments)) {
            case 1:
                $mixedLeft = $arrayArguments[0];
                $mixedRight = null;
                break;
            case 2:
                $mixedLeft = $arrayArguments[0];
                $mixedRight = $arrayArguments[1];
                break;
            case 3:
                $mixedLeft = $arrayArguments[0];
                $stringStatementType = $arrayArguments[1];
                $mixedRight = $arrayArguments[2];
                break;
            default:
                $mixedLeft = null;
                $mixedRight = null;
        }

        return $fiveCode->result(
            ___::is($mixedLeft, $stringStatementType, $mixedRight)
        );
    }

}