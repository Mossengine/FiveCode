<?php

use Mossengine\FiveCode\Helpers\___;

/**
 * Class ___Test
 */
class ___Test extends PHPUnit_Framework_TestCase
{

    public function testIfArrayIsAssociative() {
        $this->assertTrue(
            ___::arrayIsAssociative(['a' => 'A'])
        );
    }
    public function testIfNotArrayIsAssociative() {
        $this->assertFalse(
            ___::arrayIsAssociative(['a','b'])
        );
    }
    public function testIfArrayHas() {
        $this->assertTrue(
            ___::arrayHas(['a' => 'A'], 'a')
        );
    }
    public function testIfNotArrayHas() {
        $this->assertFalse(
            ___::arrayHas(['b' => 'B'], 'a')
        );
    }
    public function testIfArrayGet() {
        $this->assertEquals(
            'A',
            ___::arrayGet(['a' => 'A'], 'a')
        );
    }
    public function testIfNotArrayGet() {
        $this->assertNotEquals(
            'A',
            ___::arrayGet(['b' => 'B'], 'a', 'default')
        );
    }
    public function testIfArraySet() {
        $array = [];
        ___::arraySet($array, 'a', 'A');
        $this->assertEquals(
            [
                'a' => 'A'
            ],
            $array
        );
    }
    public function testIfNotArraySet() {
        $array = [];
        ___::arraySet($array, 'a', 'A');
        $this->assertNotEquals(
            [
                'b' => 'B'
            ],
            $array
        );
    }
    public function testIfArrayForget() {
        $array = [
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        ];
        ___::arrayForget($array, 'b');
        $this->assertEquals(
            [
                'a' => 'A',
                'c' => 'C'
            ],
            $array
        );
    }
    public function testIfNotArrayForget() {
        $array = [
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        ];
        ___::arrayForget($array, 'z');
        $this->assertNotEquals(
            [
                'a' => 'A',
                'c' => 'C'
            ],
            $array
        );
    }
    public function testIfArrayEvery() {
        $this->assertTrue(
            ___::arrayEvery(
                [
                    1,
                    2,
                    3,
                    4
                ],
                function ($value) {
                    return $value > 0;
                }
            )
        );
    }
    public function testIfNotArrayEvery() {
        $this->assertFalse(
            ___::arrayEvery(
                [
                    1,
                    2,
                    -1,
                    4
                ],
                function ($value) {
                    return $value > 0;
                }
            )
        );
    }
    public function testIfArraySome() {
        $this->assertTrue(
            ___::arraySome(
                [
                    1,
                    -1,
                    3,
                    4
                ],
                function ($value) {
                    return $value > 0;
                }
            )
        );
    }
    public function testIfNotArraySome() {
        $this->assertFalse(
            ___::arraySome(
                [
                    -1,
                    -2,
                    -1,
                    -4
                ],
                function ($value) {
                    return $value > 0;
                }
            )
        );
    }
    public function testIfArrayFirstKey() {
        $this->assertEquals(
            'a',
            ___::arrayFirstKey(
                [
                    'a' => 'A',
                    'b' => 'B',
                    'c' => 'C'
                ]
            )
        );
    }
    public function testIfNotArrayFirstKey() {
        $this->assertNotEquals(
            'b',
            ___::arrayFirstKey(
                [
                    'a' => 'A',
                    'b' => 'B',
                    'c' => 'C'
                ]
            )
        );
    }
    public function testIfArrayFirstValue() {
        $this->assertEquals(
            'A',
            ___::arrayFirstValue(
                [
                    'a' => 'A',
                    'b' => 'B',
                    'c' => 'C'
                ]
            )
        );
    }
    public function testIfNotArrayFirstValue() {
        $this->assertNotEquals(
            'B',
            ___::arrayFirstValue(
                [
                    'a' => 'A',
                    'b' => 'B',
                    'c' => 'C'
                ]
            )
        );
    }
    public function testIfIs() {
        foreach (
            [
                [1, '==', '1'],
                [1, '!=', '2'],
                [1, '===', 1],
                [1, '!==', 2],
                [2, '>', 1],
                [3, '>=', 3],
                [5, '<', 6],
                [4, '<=', 4]
            ]
            as $arrayTest
        ) {
            $this->assertTrue(
                ___::is(
                    $arrayTest[0],
                    $arrayTest[1],
                    $arrayTest[2]
                )
            );
        }
    }
    public function testIfNotIs() {
        foreach (
            [
                [1, '==', '2'],
                [1, '!=', '1'],
                [1, '===', 2],
                [1, '!==', 1],
                [1, '>', 2],
                [2, '>=', 3],
                [6, '<', 5],
                [5, '<=', 4]
            ]
            as $arrayTest
        ) {
            $this->assertFalse(
                ___::is(
                    $arrayTest[0],
                    $arrayTest[1],
                    $arrayTest[2]
                )
            );
        }
    }
    public function testIfIsAlwaysFalse() {
        $this->assertFalse(
            ___::is(
                1,
                'banana',
                1
            )
        );
    }
}