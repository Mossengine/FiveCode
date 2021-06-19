<?php

/**
 * Class InstructionsTest
 */
class InstructionsTest extends PHPUnit_Framework_TestCase
{

    public function testCanInstructions() {
        $this->assertEquals(
            'value3',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    [
                        ['value' => 'value1'],
                        ['value' => 'value2'],
                        ['value' => 'value3']
                    ]
                ])
                ->variableGet('_return', 'default')
        );
    }

    public function testCanInstructionsInInstructions() {
        $this->assertEquals(
            [
                'a' => 'value_a',
                'b' => 'value_b',
                'c' => 'value_c',
                'd' => 'value_d',
                'e' => 'value_e'
            ],
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    [
                        ['set' => ['key.a', 'value_a']],
                        ['set' => ['key.b', 'value_b']],
                        [
                            ['set' => ['key.c', 'value_c']],
                            [
                                [
                                    [
                                        [
                                            ['set' => ['key.d', 'value_d']]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        ['set' => ['key.e', 'value_e']]
                    ]
                ])
                ->variableGet('key', 'default')
        );
    }

}