<?php

/**
 * Class MathsTest
 */
class MathsTest extends PHPUnit_Framework_TestCase
{

    public function testCanAddition() {
        $this->assertEquals(
            3,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'maths' => [
                            'addition' => true
                        ]
                    ]
                ],
            ])
                ->evaluate([
                    ['call' => [
                        'maths.addition',
                        1,
                        2
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanSubtract() {
        $this->assertEquals(
            3,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'maths' => [
                            'subtract' => true
                        ]
                    ]
                ],
            ])
                ->evaluate([
                    ['call' => [
                        'maths.subtract',
                        5,
                        2
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanDivide() {
        $this->assertEquals(
            3.5,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'maths' => [
                            'divide' => true
                        ]
                    ]
                ],
            ])
                ->evaluate([
                    ['call' => [
                        'maths.divide',
                        7,
                        2
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanMultiply() {
        $this->assertEquals(
            21,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'maths' => [
                            'multiply' => true
                        ]
                    ]
                ],
            ])
                ->evaluate([
                    ['call' => [
                        'maths.multiply',
                        7,
                        3
                    ]]
                ])
                ->return(0)
        );
    }

    public function testCanRandom() {
        $intRandom = Mossengine\FiveCode\FiveCode::make([
            'functions' => [
                'allowed' => [
                    'maths' => [
                        'random' => true
                    ]
                ]
            ],
        ])
            ->evaluate([
                ['call' => [
                    'maths.random',
                    1,
                    4
                ]]
            ])
            ->return(0);
        $this->assertTrue(
            $intRandom >= 1
            && $intRandom <= 4
        );
    }

}