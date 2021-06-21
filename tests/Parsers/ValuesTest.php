<?php

/**
 * Class ValuesTest
 */
class ValuesTest extends PHPUnit_Framework_TestCase
{

    public function testCanValue() {
        $this->assertEquals(
            'value1',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['value' => 'value1']
                ])
                ->variableGet('_return', 'default')
        );
    }

    public function testCanArray() {
        $this->assertEquals(
            [
                'A',
                'B',
                'C'
            ],
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'include' => [
                        'c' => 'C'
                    ]
                ]
            ])
                ->evaluate([
                    ['array' => [
                        'A',
                        'B',
                        ['get' => ['c', 'default']]
                    ]]
                ])
                ->return('default')
        );
    }

}