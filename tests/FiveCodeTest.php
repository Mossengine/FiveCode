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

    public function testIfFunctionsAllowed() {
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make()
                ->functionsAllowed('any'),
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
                ->functionsAllowed('any'),
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
                ->functionsAllowed('any'),
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
                ->functionsAllowed('specific'),
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
                ->functionsAllowed('other'),
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
                ->functionsAllowed('specific'),
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
                ->functionsAllowed('specific'),
            'specific function allowed but All functions disallowed, specific function True'
        );
    }

    public function testIfVariablesAllowed() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->variablesAllowed('any'),
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
                ->variablesAllowed('any'),
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
                ->variablesAllowed('any', 'get'),
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
                ->variablesAllowed('any', 'set'),
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
                ->variablesAllowed('any'),
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
                ->variablesAllowed('specific'),
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
                ->variablesAllowed('specific', 'set'),
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
                ->variablesAllowed('specific', 'set'),
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
                ->variablesAllowed('specific.path.to.thing'),
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
                ->variablesAllowed('specific.path.to.thing'),
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
                ->variablesAllowed('specific.path.to.thing', 'get'),
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
                ->variablesAllowed('specific.path.to.thing', 'get'),
            'specific path variable action disallowed while all other variables allowed, specific path variable action False'
        );
    }

    public function testIfItCanSetGetAndForgetVariables() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $this->assertEquals(
            [],
            $fiveCode->variablesAll(),
            'There should be no variables set'
        );

        $fiveCode->variablesSet('a', 1);
        $this->assertEquals(
            1,
            $fiveCode->variablesGet('a', 9),
            'The value at key a should be 1'
        );

        $fiveCode->variablesForget('a');
        $this->assertEquals(
            6,
            $fiveCode->variablesGet('a', 6),
            'The value at key a should not exist and the default value of 9 should be returned.'
        );
    }
    public function testIfItCanSetGetAndForgetFunctions() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $this->assertEquals(
            [],
            $fiveCode->functionsAll(),
            'There should be no functions set'
        );

        $fiveCode->functionsSet('a', function() { return 'A'; });
        $this->assertEquals(
            'A',
            call_user_func($fiveCode->functionsGet('a')),
            'The function at key a should return A'
        );

        $fiveCode->functionsForget('a');
        $this->assertEquals(
            'Z',
            call_user_func($fiveCode->functionsGet('a', function() { return 'Z'; })),
            'The function at key a should not exist and the default function that returns Z should execute.'
        );
    }

    public function testEvaluation() {
        $fiveCode = Mossengine\FiveCode\FiveCode::make();

        $fiveCode->evaluate([
            ['variable' => [
                'set' => ['a' => 'A']
            ]]
        ]);
        $this->assertEquals(
            'A',
            $fiveCode->variablesGet('a'),
            'The variable at key a should have been set by the evaluation.'
        );
    }

    public function testEvaluationReturn() {
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
            'The return from the evaluate should be the results of the last evaluation.'
        );
    }

    public function testEvaluations() {
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
            ['evaluates' => [
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
            $fiveCode->variablesGet('d'),
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
            $fiveCode->variablesGet('sum'),
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
            $fiveCode->variablesGet('return'),
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
                ->variablesGet('return'),
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
                ->variablesGet('return'),
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
                ->variablesGet('return'),
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
                ->variablesGet('return'),
            'The return variable should be null as the min is part of the disallowed functions list.'
        );
    }

    public function testIfItCanCondition() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['condition' => [
                        'every' => [
                            'statements' => [
                                ['==' => [
                                    'arguments' => [
                                        ['value' => 5],
                                        ['value' => 5]
                                    ]
                                ]]
                            ]
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
                        'every' => [
                            'statements' => [
                                ['==' => [
                                    'arguments' => [
                                        ['value' => 4],
                                        ['value' => 5]
                                    ]
                                ]]
                            ]
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
                        'every' => [
                            'statements' => [
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
                        'every' => [
                            'statements' => [
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
                        ]
                    ]],
                ])
                ->return(),
            'The return from the evaluate should be failure based on the condition not being true and triggering the false to set return variable to failure'
        );
    }
}