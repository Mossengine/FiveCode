<?php

/**
 * Class FiveCodeTest
 */
class FiveCodeTest extends PHPUnit_Framework_TestCase
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

    public function testIfFunctionsAllowed() {
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make()
                ->isFunctionAllowed('any'),
            'No functions indicated, any function False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => true
                    ]
                ]
            ])
                ->isFunctionAllowed('any'),
            'All functions allowed, Any function True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => false
                    ]
                ]
            ])
                ->isFunctionAllowed('any'),
            'All functions disallowed, any function False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'specific' => true
                    ]
                ]
            ])
                ->isFunctionAllowed('specific'),
            'specific function allowed, specific function True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'specific' => true
                    ]
                ]
            ])
                ->isFunctionAllowed('other'),
            'specific function allowed, other function False'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => true,
                        'specific' => false
                    ]
                ]
            ])
                ->isFunctionAllowed('specific'),
            'All functions allowed but specific function disallowed, specific function False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => false,
                        'specific' => true
                    ]
                ]
            ])
                ->isFunctionAllowed('specific'),
            'specific function allowed but All functions disallowed, specific function True'
        );
    }

    public function testIfVariablesAllowed() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->isVariableAllowed('any'),
            'No variables indicated, any variable True'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => true
                    ]
                ]
            ])
                ->isVariableAllowed('any'),
            'All variables allowed, Any variable True'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => [
                            'get' => true,
                            'set' => false,
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('any', 'get'),
            'All variables allowed for specific action while another action disallowed, specific variable action True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => [
                            'get' => true,
                            'set' => false,
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('any', 'set'),
            'All variables disallowed for specific action while another action allowed, specific variable action False'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => false
                    ]
                ]
            ])
                ->isVariableAllowed('any'),
            'All variables disallowed, any variable False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => false,
                        'specific' => true
                    ]
                ]
            ])
                ->isVariableAllowed('specific'),
            'specific variable allowed while all variables disallowed, specific variable True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => false,
                        'specific' => [
                            'get' => true
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific', 'set'),
            'specific variable action allowed while all other variables disallowed, specific other action variable False'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => [
                            'set' => false
                        ],
                        'specific' => [
                            'get' => true
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific', 'set'),
            'specific variable action allowed while all other variables action disallowed, specific other action with other variables False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => false,
                        'specific' => [
                            'path' => [
                                'to' => [
                                    'thing' => true
                                ]
                            ]
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific.path.to.thing'),
            'specific path variable allowed while all other variables disallowed, specific path variable True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => true,
                        'specific' => [
                            'path' => [
                                'to' => [
                                    'thing' => false
                                ]
                            ]
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific.path.to.thing'),
            'specific path variable disallowed while all other variables allowed, specific path variable False'
        );
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => false,
                        'specific' => [
                            'path' => [
                                'to' => [
                                    'thing' => [
                                        'get' => true
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific.path.to.thing', 'get'),
            'specific path variable action allowed while all other variables disallowed, specific path variable action True'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'allowed' => [
                        '*' => true,
                        'specific' => [
                            'path' => [
                                'to' => [
                                    'thing' => [
                                        'get' => false
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
                ->isVariableAllowed('specific.path.to.thing', 'get'),
            'specific path variable action disallowed while all other variables allowed, specific path variable action False'
        );
    }

    public function testIfValues() {
        $this->assertEquals(
            'a',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['value' => 'a']
                ])
                ->return()
        );
        $this->assertEquals(
            'c',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['values' => ['a', 'b', 'c']]
                ])
                ->return()
        );
        $this->assertEquals(
            'yes',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['value' => 'a'],
                    ['value' => 'b'],
                    ['condition' => [
                        'all' => [
                            ['==' => [
                                'arguments' => [
                                    ['value' => 5],
                                    ['value' => 5]
                                ]
                            ]]
                        ],
                        'true' => [
                            ['value' => 'yes']
                        ],
                        'false' => [
                            ['value' => 'no']
                        ]
                    ]]
                ])
                ->return()
        );
    }

    public function testIfItCanSetGetAndForgetVariables() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $this->assertEquals(
            [],
            $fiveCode->variables(),
            'There should be no variables set'
        );

        $fiveCode->variableSet('a', 1);
        $this->assertEquals(
            1,
            $fiveCode->variableGet('a', 9),
            'The value at key a should be 1'
        );

        $fiveCode->variableForget('a');
        $this->assertEquals(
            6,
            $fiveCode->variableGet('a', 6),
            'The value at key a should not exist and the default value of 9 should be returned.'
        );

        $fiveCode->variableSet('m', 'M');
        $this->assertEquals(
            'M',
            $fiveCode
                ->evaluate([
                    ['variables' => [
                        [
                            'set' => [
                                'key' => 'n',
                                'variable' => 'm'
                            ]
                        ],
                        [
                            'get' => ['n' => 'Z']
                        ]
                    ]]
                ])
                ->return(),
            'The value at key n should be M'
        );
    }

    public function testIfItCanSetGetAndForgetFunctions() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $this->assertEquals(
            [],
            $fiveCode->functions(),
            'There should be no functions set'
        );

        $fiveCode->functionSet('a', function() { return 'A'; });
        $this->assertEquals(
            'A',
            call_user_func($fiveCode->functionGet('a')),
            'The function at key a should return A'
        );

        $fiveCode->functionForget('a');
        $this->assertEquals(
            'Z',
            call_user_func($fiveCode->functionGet('a', function() { return 'Z'; })),
            'The function at key a should not exist and the default function that returns Z should execute.'
        );
    }

    public function testInstruction() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $fiveCode->evaluate([
            ['variable' => [
                'set' => ['a' => 'A']
            ]]
        ]);
        $this->assertEquals(
            'A',
            $fiveCode->variableGet('a'),
            'The variable at key a should have been set by the instruction.'
        );
    }

    public function testInstructionReturn() {
        $this->assertEquals(
            20,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'default' => [
                        'a' => function($a, $b) { return $a + $b; }
                    ]
                ]
            ])
                ->evaluate([
                    ['variables' => [
                        ['set' => ['a' => 5]],
                        ['set' => ['b' => 15]]
                    ]],
                    ['execute' => [
                        'a' => [
                            'arguments' => [
                                ['variable' => 'a'],
                                ['variable' => 'b']
                            ]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be the results of the last instruction.'
        );
    }

    public function testInstructions() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make([
            'functions' => [
                'default' => [
                    'a' => function($a, $b) { return $a + $b; }
                ]
            ]
        ]);

        $fiveCode->evaluate([
            ['variables' => [
                ['set' => ['a' => 5]],
                ['set' => ['b' => 15]]
            ]],
            ['execute' => [
                'a' => [
                    'arguments' => [
                        ['variable' => 'a'],
                        ['variable' => 'b']
                    ],
                    'returns' => [
                        ['variable' => 'c'],
                    ]
                ]
            ]],
            ['instructions' => [
                ['execute' => [
                    'a' => [
                        'arguments' => [
                            ['variable' => 'c'],
                            ['value' => 50]
                        ],
                        'returns' => [
                            ['variable' => 'd']
                        ]
                    ]
                ]]
            ]]
        ]);
        $this->assertEquals(
            70,
            $fiveCode->variableGet('d'),
            'The variable at key d should have been set by the execute return which should be 70.'
        );
    }

    public function testIfItCanExecutePhpFunctions() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make([
            'functions' => [
                'allowed' => [
                    'array_sum' => true
                ]
            ]
        ]);

        $fiveCode->evaluate([
            ['variable' => [
                'set' => ['array' => [1,2,3]]
            ]],
            ['execute' => [
                'array_sum' => [
                    'arguments' => [
                        ['variable' => 'array']
                    ],
                    'returns' => [
                        ['variable' => 'sum'],
                    ]
                ]
            ]]
        ]);
        $this->assertEquals(
            6,
            $fiveCode->variableGet('sum'),
            'The variable at key sum should have been set by the execute return which should be 6.'
        );
    }

    public function testIfItCanReturn() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make([
            'functions' => [
                'allowed' => [
                    'array_sum' => true
                ]
            ]
        ]);

        $fiveCode->evaluate([
            ['variable' => [
                'set' => ['array' => [1,2,3]]
            ]],
            ['execute' => [
                'array_sum' => [
                    'arguments' => [
                        ['variable' => 'array']
                    ]
                ]
            ]]
        ]);
        $this->assertEquals(
            6,
            $fiveCode->variableGet('return'),
            'The return variable should have been set by the execute and should be 6.'
        );
    }

    public function testIfItCanCoreFunctionAllowAndDisallow() {
        $this->assertEquals(
            1,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        '*' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['variable' => [
                        'set' => ['array' => [1,2,3]]
                    ]],
                    ['execute' => [
                        'min' => [
                            'arguments' => [
                                ['variable' => 'array']
                            ]
                        ]
                    ]]
                ])
                ->variableGet('return'),
            'The return variable should be 1 as the allowed functions is an empty array allowing all.'
        );

        $this->assertEquals(
            6,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'array_sum' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['variable' => [
                        'set' => ['array' => [1,2,3]]
                    ]],
                    ['execute' => [
                        'array_sum' => [
                            'arguments' => [
                                ['variable' => 'array']
                            ]
                        ]
                    ]]
                ])
                ->variableGet('return'),
            'The return variable should be the sum of the array as the array_sum function should have been allowed and executed.'
        );

        $this->assertEquals(
            null,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'array_sum' => true
                    ]
                ]
            ])
                ->evaluate([
                    ['variable' => [
                        'set' => ['array' => [1,2,3]]
                    ]],
                    ['execute' => [
                        'min' => [
                            'arguments' => [
                                ['variable' => 'array']
                            ]
                        ]
                    ]]
                ])
                ->variableGet('return'),
            'The return variable should be null as the min is not part of the allowed functions list.'
        );

        $this->assertEquals(
            null,
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'min' => false
                    ]
                ]
            ])
                ->evaluate([
                    ['variable' => [
                        'set' => ['array' => [1,2,3]]
                    ]],
                    ['execute' => [
                        'min' => [
                            'arguments' => [
                                ['variable' => 'array']
                            ]
                        ]
                    ]]
                ])
                ->variableGet('return'),
            'The return variable should be null as the min is part of the disallowed functions list.'
        );
    }

    public function testIfItCanCondition() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'all' => [
                            ['==' => [
                                'arguments' => [
                                    ['value' => 5],
                                    ['value' => 5]
                                ]
                            ]]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be true based on the arguments being the same.'
        );
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'all' => [
                            ['==' => [
                                'arguments' => [
                                    ['value' => 4],
                                    ['value' => 5]
                                ]
                            ]]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be false based on the first argument not being the same as the second argument.'
        );
    }

    public function testIfItCanConditionTrueAndFalse() {
        $this->assertEquals(
            'success',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'all' => [
                            ['==' => [
                                'arguments' => [
                                    ['value' => 5],
                                    ['value' => 5]
                                ],
                                'true' => [
                                    ['variable' => [
                                        'set' => ['return' => 'success']
                                    ]]
                                ]
                            ]]
                        ],
                        'true' => [
                            ['variable' => [
                                'get' => ['return' => 'failure']
                            ]]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be success based on the condition being true and triggering the true to set return variable to success'
        );
        $this->assertEquals(
            'failure',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'all' => [
                            ['!=' => [
                                'arguments' => [
                                    ['value' => 5],
                                    ['value' => 5]
                                ],
                                'true' => [
                                    ['variable' => [
                                        'set' => ['return' => 'success']
                                    ]]
                                ],
                                'false' => [
                                    ['variable' => [
                                        'set' => ['return' => 'failure']
                                    ]]
                                ]
                            ]]
                        ],
                        'false' => [
                            ['variable' => [
                                'get' => ['return' => 'success']
                            ]]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be failure based on the condition not being true and triggering the false to set return variable to failure'
        );
    }

    public function testIfItCanConditionWithConditions() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'all' => [
                            ['==' => [
                                'arguments' => [
                                    ['value' => 5],
                                    ['value' => '5']
                                ]
                            ]],
                            ['===' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => 1]
                                ]
                            ]],
                            ['!=' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => '2']
                                ]
                            ]],
                            ['!==' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => 2]
                                ]
                            ]],
                            ['>' => [
                                'arguments' => [
                                    ['value' => 2],
                                    ['value' => 1]
                                ]
                            ]],
                            ['>=' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => 1]
                                ]
                            ]],
                            ['<' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => 2]
                                ]
                            ]],
                            ['<=' => [
                                'arguments' => [
                                    ['value' => 1],
                                    ['value' => 1]
                                ]
                            ]],
                            ['condition' => [
                                'any' => [
                                    ['===' => [
                                        'arguments' => [
                                            ['value' => 5],
                                            ['value' => 4]
                                        ]
                                    ]],
                                    ['>' => [
                                        'arguments' => [
                                            ['value' => 1],
                                            ['value' => 2]
                                        ]
                                    ]],
                                    ['<' => [
                                        'arguments' => [
                                            ['value' => 1],
                                            ['value' => 2]
                                        ]
                                    ]]
                                ]
                            ]]
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be true based on the conditions being met for all expected'
        );
    }
    /*
    public function testIfItCanIterator() {
        $this->assertEquals(
            [
                0 => 'a0',
                1 => 'a1',
                2 => 'a2',

            ],
            Mossengine\FiveCode\FiveCode::make([
                'functions' => [
                    'allowed' => [
                        'implode' => true
                    ]
                ]
            ])
                ->evaluate([
                    'iterator' => [
                        'for' => [
                            'start' => 0,
                            'limit' => 2,
                            'step' => 1,
                            'instructions' => [
                                [
                                    'execute' => [
                                        'implode' => [
                                            'arguments' => [
                                                ['value' => '']
                                            ]
                                        ]
                                    ]
                                ],
                                [
                                    'variable' => [
                                        'set'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ])
                ->return(),
            ''
        );
    }
    */
}