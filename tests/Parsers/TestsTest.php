<?php

use Mossengine\FiveCode\Parsers\Tests;

/**
 * Class TestsTest
 */
class TestsTest extends PHPUnit_Framework_TestCase
{

    public function testCanTest1() {
        $this->assertEquals(
            'abc : abc',
            Mossengine\FiveCode\FiveCode::make([
                'parsers' => [
                    'include' => [
                        'tests' => Tests::class
                    ]
                ]
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
                        'tests' => Tests::class
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