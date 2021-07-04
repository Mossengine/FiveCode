<?php

use Mossengine\FiveCode\Parsers\Parsers;

/**
 * Class ParsersTest
 */
class ParsersTest extends PHPUnit_Framework_TestCase
{

    public function testCanTest1() {
        $this->assertEquals(
            'abc : abc',
            Mossengine\FiveCode\FiveCode::make([
                'parsers' => [
                    'include' => [
                        'parsers' => Parsers::class
                    ]
                ],
                'debug' => true
            ])
                ->evaluate([
                    ['test1' => 'abc']
                ])
                ->return('default')
        );
    }

    public function testCanTest2() {
        $this->assertEquals(
            'bc',
            Mossengine\FiveCode\FiveCode::make([
                'parsers' => [
                    'include' => [
                        'parsers' => Parsers::class
                    ]
                ]
            ])
                ->evaluate([
                    ['test2' => 'abc']
                ])
                ->return('default')
        );
    }

}