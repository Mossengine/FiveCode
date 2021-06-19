<?php

/**
 * Class ConstructionTest
 */
class ConstructionTest extends PHPUnit_Framework_TestCase
{

    public function testIfConstructable() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;
        $this->assertTrue($fiveCode instanceof Mossengine\FiveCode\FiveCode);
    }

    public function testIfCanBeMade() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();
        $this->assertTrue($fiveCode instanceof Mossengine\FiveCode\FiveCode);
    }

    public function testIfCanBeMadeWithConstructors() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make([
            'functions' => [
                'default' => [
                    'a' => function() { return 'A'; }
                ],
                'allowed' => [
                    'a' => true
                ]
            ],
            'variables' => [
                'default' => [
                    'a' => 'A',
                    'b' => [
                        'c' => [
                            'd' => 'D'
                        ]
                    ]
                ],
                'allowed' => [
                    'a' => true,
                    'b' => [
                        'c' => [
                            'get' => true
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals(
            [
                'a' => function() { return 'A'; }
            ],
            $fiveCode->functions()
        );
        $this->assertEquals(
            [
                'a' => true
            ],
            $fiveCode->functionsAllowed()
        );
        $this->assertEquals(
            [
                'a' => 'A',
                'b' => [
                    'c' => [
                        'd' => 'D'
                    ]
                ]
            ],
            $fiveCode->variables()
        );
        $this->assertEquals(
            [
                'a' => true,
                'b' => [
                    'c' => [
                        'get' => true
                    ]
                ]
            ],
            $fiveCode->variablesAllowed()
        );
    }

}