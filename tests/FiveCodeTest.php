<?php

use Mossengine\FiveCode\Exceptions\ParserNotAllowedException;
use Mossengine\FiveCode\Exceptions\ParserNotFoundException;
use Mossengine\FiveCode\Parsers\Tests;

/**
 * Class ConstructionTest
 */
class FiveCodeTest extends PHPUnit_Framework_TestCase
{

    public function testIfIsDebug() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;
        $this->assertFalse($fiveCode->isDebug());
        $fiveCode->isDebug(true);
        $this->assertTrue($fiveCode->isDebug());
    }

    public function testIfLoopAdjust() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopAdjust('test1', 5);
        $fiveCode->loopAdjust('test2', 15);

        $this->assertEquals(
            5,
            $fiveCode->loopGet('test1')
        );
        $this->assertEquals(
           15,
            $fiveCode->loopGet('test2')
        );
        $this->assertNotEquals(
            $fiveCode->loopGet('test1'),
            $fiveCode->loopGet('test2')
        );
    }
    public function testIfLoopGet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $this->assertEquals(
            0,
            $fiveCode->loopGet('test')
        );
        $this->assertEquals(
            9,
            $fiveCode->loopGet('test', 9)
        );
        $fiveCode->loopSet('test', 87);
        $this->assertEquals(
            87,
            $fiveCode->loopGet('test')
        );
    }
    public function testIfLoopSet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopSet('test', 5);
        $this->assertEquals(
            5,
            $fiveCode->loopGet('test')
        );
        $this->assertEquals(
            0,
            $fiveCode->loopGet('test1')
        );
    }
    public function testIfLoopUp() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopUp('test');
        $this->assertEquals(
            1,
            $fiveCode->loopGet('test')
        );
        $fiveCode->loopUp('test');
        $fiveCode->loopUp('test');
        $this->assertEquals(
            3,
            $fiveCode->loopGet('test')
        );
    }
    public function testIfLoopDown() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopUp('test');
        $fiveCode->loopUp('test');
        $fiveCode->loopUp('test');
        $fiveCode->loopDown('test');
        $this->assertEquals(
            2,
            $fiveCode->loopGet('test')
        );
        $fiveCode->loopDown('test');
        $this->assertEquals(
            1,
            $fiveCode->loopGet('test')
        );
    }
    public function testIfIsLoopOver() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopAdjust('test', 6);
        $this->assertTrue(
            $fiveCode->isLoopOver('test', 5)
        );
        $this->assertFalse(
            $fiveCode->isLoopOver('test', 6)
        );
    }
    public function testIfIsLoopUnder() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->loopAdjust('test', 4);
        $this->assertTrue(
            $fiveCode->isLoopUnder('test', 5)
        );
        $this->assertFalse(
            $fiveCode->isLoopUnder('test', 4)
        );
    }

    public function testIfFunctions() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functions([
            'a' => function() { return null; },
            'b' => function() { return null; }
        ]);
        $this->assertEquals(
            [
                'a' => function() { return null; },
                'b' => function() { return null; }
            ],
            array_intersect_key(
                $fiveCode->functions(),
                [
                    'a' => true,
                    'b' => true
                ]
            )
        );
    }
    public function testIfFunctionAdd() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functionAdd(
            'a',
            function() { return null; }
        );
        $this->assertEquals(
            [
                'a' => function() { return null; },
            ],
            array_intersect_key(
                $fiveCode->functions(),
                [
                    'a' => true,
                ]
            )
        );
    }
    public function testIfFunctionSet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functionSet(
            'a',
            function() { return null; }
        );
        $this->assertEquals(
            [
                'a' => function() { return null; },
            ],
            array_intersect_key(
                $fiveCode->functions(),
                [
                    'a' => true,
                ]
            )
        );
    }
    public function testIfFunctionGet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functionSet(
            'a',
            function() { return null; }
        );
        $this->assertEquals(
            function() { return null; },
            $fiveCode->functionGet('a')
        );
    }
    public function testIfFunctionForget() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functionForget('maths');
        $this->assertEquals(
            [],
            $fiveCode->functions()
        );
    }
    public function testIfIsFunctionAllowed() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->functionsAllowed([
            'a' => true,
            'b' => false
        ]);
        $this->assertTrue(
            $fiveCode->isFunctionAllowed('a')
        );
        $this->assertFalse(
            $fiveCode->isFunctionAllowed('b')
        );
    }

    public function testIfVariables() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variables([
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        ]);
        $this->assertEquals(
            [
                'a' => 'A',
                'b' => 'B',
                'c' => 'C'
            ],
            $fiveCode->variables()
        );
    }
    public function testIfVariableSet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variableSet(
            'a',
            'A'
        );
        $this->assertEquals(
            [
                'a' => 'A'
            ],
            $fiveCode->variables()
        );
    }
    public function testIfVariableGet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variables([
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        ]);
        $this->assertEquals(
            'B',
            $fiveCode->variableGet('b', 'default')
        );
    }
    public function testIfVariableForget() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variables([
            'a' => 'A',
            'b' => 'B',
            'c' => 'C'
        ]);
        $fiveCode->variableForget('b');
        $this->assertEquals(
            'default',
            $fiveCode->variableGet('b', 'default')
        );
    }
    public function testIfVariablesAllowed() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variablesAllowed([
            'a' => true,
            'b' => [
                'get' => true,
                'set' => false,
            ],
            'c' => false
        ]);
        $this->assertEquals(
            [
                'a' => true,
                'b' => [
                    'get' => true,
                    'set' => false,
                ],
                'c' => false
            ],
            $fiveCode->variablesAllowed()
        );
    }
    public function testIfIsVariableAllowed() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->variablesAllowed([
            'a' => true,
            'b' => [
                'get' => true,
                'set' => false,
            ],
            'c' => false
        ]);
        $this->assertTrue($fiveCode->isVariableAllowed('a'));
        $this->assertTrue($fiveCode->isVariableAllowed('b', 'get'));
        $this->assertFalse($fiveCode->isVariableAllowed('b', 'set'));
        $this->assertFalse($fiveCode->isVariableAllowed('c'));
    }
    public function testIfParsers() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parsers([
            'tests' => Tests::class
        ]);
        $this->assertEquals(
            [
                'test1' => function() { return; },
                'test2' => function() { return; },
            ],
            array_intersect_key(
                $fiveCode->parsers(),
                [
                    'test1' => true,
                    'test2' => true
                ]
            )
        );
    }
    public function testIfParserSet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parserSet(
            'test3',
            function() { return; }
        );
        $this->assertEquals(
            [
                'test3' => function() { return; }
            ],
            array_intersect_key(
                $fiveCode->parsers(),
                [
                    'test3' => true
                ]
            )
        );
    }
    public function testIfParserGet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parserSet(
            'test3',
            function() { return; }
        );
        $this->assertEquals(
            function() { return; },
            $fiveCode->parserGet('test3')
        );
    }
    public function testIfParserForget() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parsers([
            'tests' => Tests::class
        ]);
        $fiveCode->parserForget('test1');
        $this->assertEquals(
            [
                'test2' => function() { return; }
            ],
            array_intersect_key(
                $fiveCode->parsers(),
                [
                    'test1' => true,
                    'test2' => true
                ]
            )
        );
    }
    public function testIfParsersAllowed() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parsersAllowed([
            'test1' => true,
            'test2' => false
        ]);
        $this->assertEquals(
            [
                'test1' => true,
                'test2' => false
            ],
            $fiveCode->parsersAllowed()
        );
    }
    public function testIfIfParserAllowed() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->parsers([
            'tests' => Tests::class
        ]);
        $fiveCode->parsersAllowed([
            'test1' => true,
            'test2' => false
        ]);
        $this->assertTrue($fiveCode->isParserAllowed('test1'));
        $this->assertFalse($fiveCode->isParserAllowed('test2'));
    }
    public function testIfSettings() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->settings([
            'a' => [
                'b' => true,
                'c' => 5,
                'd' => 'D'
            ]
        ]);
        $this->assertEquals(
            [
                'a' => [
                    'b' => true,
                    'c' => 5,
                    'd' => 'D'
                ]
            ],
            array_intersect_key(
                $fiveCode->settings(),
                [
                    'a' => true
                ]
            )
        );
    }
    public function testIfSettingSet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->settingSet(
            'a',
            'A'
        );
        $this->assertEquals(
            [
                'a' => 'A'
            ],
            array_intersect_key(
                $fiveCode->settings(),
                [
                    'a' => true
                ]
            )
        );
    }
    public function testIfSettingGet() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->settings([
            'a' => [
                'b' => true,
                'c' => 5,
                'd' => 'D'
            ]
        ]);
        $this->assertEquals(
            [
                'b' => true,
                'c' => 5,
                'd' => 'D'
            ],
            $fiveCode->settingGet('a')
        );
        $this->assertEquals(
            true,
            $fiveCode->settingGet('a.b')
        );
        $this->assertEquals(
            5,
            $fiveCode->settingGet('a.c')
        );
        $this->assertEquals(
            'D',
            $fiveCode->settingGet('a.d')
        );
    }
    public function testIfSettingForget() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->settings([
            'a' => [
                'b' => true,
                'c' => 5,
                'd' => 'D'
            ]
        ]);
        $fiveCode->settingForget('a.c');
        $this->assertEquals(
            [
                'a' => [
                    'b' => true,
                    'd' => 'D'
                ]
            ],
            array_intersect_key(
                $fiveCode->settings(),
                [
                    'a' => true
                ]
            )
        );
    }
    public function testIfSettingsMerge() {
        $fiveCode = new Mossengine\FiveCode\FiveCode;

        $fiveCode->settings([
            'a' => [
                'b' => true,
                'c' => 5,
                'd' => 'D'
            ]
        ]);
        $fiveCode->settingsMerge([
            'a' => [
                'b' => true,
                'c' => 6,
                'e' => 'E'
            ],
            'b' => 'B'
        ]);
        $this->assertEquals(
            [
                'a' => [
                    'b' => true,
                    'c' => 6,
                    'd' => 'D',
                    'e' => 'E'
                ],
                'b' => 'B'
            ],
            array_intersect_key(
                $fiveCode->settings(),
                [
                    'a' => true,
                    'b' => true
                ]
            )
        );
    }

    public function testIfParserNotAllowedException() {
        $this->expectException(ParserNotAllowedException::class);
        Mossengine\FiveCode\FiveCode::make([
            'parsers' => [
                'allowed' => [
                    'set' => false
                ]
            ]
        ])
            ->evaluate([
                ['set' => ['key1', 'value1']]
            ]);
    }
    public function testIfParserNotFoundException() {
        $this->expectException(ParserNotFoundException::class);
        Mossengine\FiveCode\FiveCode::make()
            ->evaluate([
                ['banana' => []]
            ]);
    }

}