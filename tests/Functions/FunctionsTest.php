<?php

use Mossengine\FiveCode\Functions\Functions;

/**
 * Class FunctionsTest
 */
class FunctionsTest extends PHPUnit_Framework_TestCase
{

    public function testCanAddition() {
        $this->assertEquals(
            3,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'include' => [
                        Functions::class
                    ],
                    'allowed' => [
                        'functions' => [
                            'one' => true
                        ]
                    ]
                ],
            ])
                ->evaluate([
                    ['call' => [
                        'functions.one',
                        1,
                        2
                    ]]
                ])
                ->return(0)
        );
    }

}