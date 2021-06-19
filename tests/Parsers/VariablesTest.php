<?php

/**
 * Class VariablesTest
 */
class VariablesTest extends PHPUnit_Framework_TestCase
{

    public function testCanGetVariable() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'default' => [
                        'key1' => 'value1'
                    ]
                ]
            ])
                ->variableGet('key1')
        );
    }

    public function testCanSetVariable() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make()
                ->variableSet('key1', 'value1')
                ->variableGet('key1')
        );
    }

    public function testCanForgetVariable() {
        $this->assertEquals(
            'default',
            Mossengine\FiveCode\FiveCode::make()
                ->variableSet('key1', 'value1')
                ->variableForget('key1')
                ->variableGet('key1', 'default')
        );
    }

    public function testInstructionValue() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['value' => 'value1']
                ])
                ->return('default')
        );
    }

    public function testInstructionSet() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['set' => [
                        'key1',
                        ['value' => 'value1']
                    ]]
                ])
                ->variableGet('key1')
        );
    }

    public function testInstructionGet() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'default' => [
                        'key1' => 'value1'
                    ]
                ]
            ])
                ->evaluate([
                    ['get' => [
                        'key1',
                        ['value' => 'default']
                    ]]
                ])
                ->variableGet('key1')
        );
    }

    public function testInstructionSetWithGet() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'default' => [
                        'key1' => 'value1'
                    ]
                ]
            ])
                ->evaluate([
                    ['set' => [
                        'key2',
                        ['get' => [
                            'key1',
                            ['value' => 'default']
                        ]]
                    ]]
                ])
                ->variableGet('key1')
        );
    }

    public function testInstructionForget() {
        $this->assertEquals(
            'default',
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'default' => [
                        'key1' => 'value1'
                    ]
                ]
            ])
                ->evaluate([
                    ['forget' => [
                        'key1'
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }
}