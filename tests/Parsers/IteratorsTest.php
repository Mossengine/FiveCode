<?php

/**
 * Class IteratorsTest
 */
class IteratorsTest extends PHPUnit_Framework_TestCase
{

    public function testCanForLimit() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['for' => [
                        [5],
                        [
                            ['set' => [
                                'key1',
                                ['get' => [
                                    '_iterator.for.index'
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanForStartToLimit() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['for' => [
                        [3, 5],
                        [
                            ['set' => [
                                'key1',
                                ['get' => [
                                    '_iterator.for.index'
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanForStartToLimitWhileStepping() {
        $this->assertEquals(
            4,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['for' => [
                        [2, 5, 2],
                        [
                            ['set' => [
                                'key1',
                                ['get' => [
                                    '_iterator.for.index'
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanEachNonAssociativeArray() {
        $this->assertEquals(
            [
                'index' => 3,
                'item' => 'eight'
            ],
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['each' => [
                        [
                            5,
                            6,
                            7,
                            'eight'
                        ],
                        [
                            ['set' => [
                                'key1.index',
                                ['get' => [
                                    '_iterator.each.index'
                                ]]
                            ]],
                            ['set' => [
                                'key1.item',
                                ['get' => [
                                    '_iterator.each.item'
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanEachAssociativeArray() {
        $this->assertEquals(
            [
                'index' => 8,
                'item' => 'eight'
            ],
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['each' => [
                        [
                            'five' => 5,
                            'six' => 6,
                            'seven' => 7,
                            8 => 'eight'
                        ],
                        [
                            ['set' => [
                                'key1.index',
                                ['get' => [
                                    '_iterator.each.index'
                                ]]
                            ]],
                            ['set' => [
                                'key1.item',
                                ['get' => [
                                    '_iterator.each.item'
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanDo() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['do' => [
                        [
                            ['set' => [
                                'key1',
                                ['get' => [
                                    '_iterator.do.iteration',
                                    0
                                ]]
                            ]]
                        ],
                        [
                            ['<' => [
                                ['get' => [
                                    'key1',
                                    0
                                ]],
                                5
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanDoComplex() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'array_sum' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['do' => [
                        [
                            ['set' => [
                                'key1',
                                ['call' => [
                                    'array_sum',
                                    [
                                        ['array' => [
                                            ['get' => [
                                                'key1',
                                                0
                                            ]],
                                            1
                                        ]]
                                    ]
                                ]]
                            ]]
                        ],
                        [
                            ['<' => [
                                ['get' => [
                                    'key1',
                                    0
                                ]],
                                5
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanWhile() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['while' => [
                        [
                            ['<' => [
                                ['get' => [
                                    'key1',
                                    0
                                ]],
                                5
                            ]]
                        ],
                        [
                            ['set' => [
                                'key1',
                                ['get' => [
                                    '_iterator.while.iteration',
                                    0
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }

    public function testCanWhileComplex() {
        $this->assertEquals(
            5,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'array_sum' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['while' => [
                        [
                            ['<' => [
                                ['get' => [
                                    'key1',
                                    0
                                ]],
                                5
                            ]]
                        ],
                        [
                            ['set' => [
                                'key1',
                                ['call' => [
                                    'array_sum',
                                    [
                                        ['array' => [
                                            ['get' => [
                                                'key1',
                                                0
                                            ]],
                                            1
                                        ]]
                                    ]
                                ]]
                            ]]
                        ]
                    ]]
                ])
                ->variableGet('key1', 'default')
        );
    }
}