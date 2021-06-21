<?php

/**
 * Class ExecutesTest
 */
class ExecutesTest extends PHPUnit_Framework_TestCase
{

    public function testCanCallSystemFunctions() {
        $this->assertEquals(
            6,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['call' => [
                        'array_sum',
                        ['value' => [1,2,3]]
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanCallDefinedFunctions() {
        $this->assertEquals(
            3,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'include' => [
                        '_add' => function($fivecode, $data) { return $data[0] + $data[1]; }
                    ],
                    'allowed' => [
                        '*' => false,
                        '_add' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['call' => [
                        '_add',
                        ['value' => 1],
                        ['value' => 2]
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanNotCallSystemFunctionsWhenNotEnabled() {
        $this->assertEquals(
            0,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['call' => [
                        'array_sum',
                        ['value' => [1,2,3]]
                    ]]
                ])
                ->return(0)
        );
    }

}