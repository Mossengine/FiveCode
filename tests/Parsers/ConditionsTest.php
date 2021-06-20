<?php

/**
 * Class ConditionsTest
 */
class ConditionsTest extends PHPUnit_Framework_TestCase
{

    public function testCanIsLike() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['==' => [1, '1']]
                ])
                ->return(false)
        );
    }

    public function testCanIsSame() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['===' => [1, 1]]
                ])
                ->return(false)
        );
    }

    public function testCanIsNotLike() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['!=' => [1, '2']]
                ])
                ->return(false)
        );
    }

    public function testCanIsNotSame() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['!==' => [1, 2]]
                ])
                ->return(false)
        );
    }

    public function testCanIsMore() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['>' => [2, 1]]
                ])
                ->return(false)
        );
    }

    public function testCanIsMoreOrSame() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['>=' => [2, 2]]
                ])
                ->return(false)
        );
    }

    public function testCanIsLess() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['<' => [1, 2]]
                ])
                ->return(false)
        );
    }

    public function testCanIsLessOrSame() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['<=' => [2, 2]]
                ])
                ->return(false)
        );
    }

    public function testCanIsSameGetVariables() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make([
                'variables' => [
                    'include' => [
                        'key1' => 1,
                        'key2' => 1
                    ]
                ]
            ])
                ->evaluate([
                    ['===' => [
                        ['get' => [
                            'key1',
                            ['value' => 'default1']
                        ]],
                        ['get' => [
                            'key2',
                            ['value' => 'default2']
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIsAllSame() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['all' => [
                        ['===' => [1, 1]],
                        ['===' => [1, 1]],
                        ['===' => [1, 1]],
                        ['===' => [1, 1]],
                        ['===' => [1, 1]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIsAllSameInstruction() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['all' => ['===' => [1, 1]]]
                ])
                ->return(false)
        );
    }

    public function testCanIsNotAllSame() {
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['all' => [
                        ['===' => [1, 1]],
                        ['===' => [1, 1]],
                        ['===' => [1, 2]],
                        ['===' => [1, 1]],
                        ['===' => [1, 1]]
                    ]]
                ])
                ->return(true)
        );
    }

    public function testCanIsAny() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['any' => [
                        ['===' => [1, 2]],
                        ['===' => [1, 3]],
                        ['===' => [1, 4]],
                        ['===' => [1, 1]],
                        ['===' => [1, 5]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIsNotAny() {
        $this->assertFalse(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['any' => [
                        ['===' => [1, 2]],
                        ['===' => [1, 3]],
                        ['===' => [1, 4]],
                        ['===' => [1, 5]],
                        ['===' => [1, 6]]
                    ]]
                ])
                ->return(true)
        );
    }

    public function testCanIfCondition() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['===' => [1, 1]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfConditions() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        [
                            ['===' => [1, 3]],
                            ['===' => [1, 2]],
                            ['===' => [1, 1]]
                        ]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfAll() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['all' => [
                            ['===' => [1, 1]],
                            ['===' => [1, 1]],
                            ['===' => [1, 1]]
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfAny() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['any' => [
                            ['===' => [1, 2]],
                            ['===' => [1, 1]],
                            ['===' => [1, 3]]
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfNested() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['any' => [
                            ['all' => [
                                ['===' => [1, 1]],
                                ['===' => [1, 1]],
                                ['===' => [1, 1]]
                            ]],
                            ['any' => [
                                ['===' => [1, 2]],
                                ['===' => [1, 3]],
                                ['===' => [1, 4]]
                            ]]
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfNestedReversed() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['any' => [
                            ['all' => [
                                ['===' => [1, 1]],
                                ['===' => [1, 2]],
                                ['===' => [1, 1]]
                            ]],
                            ['any' => [
                                ['===' => [1, 2]],
                                ['===' => [1, 1]],
                                ['===' => [1, 4]]
                            ]]
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfComplex() {
        $this->assertTrue(
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['all' => [
                            ['any' => [
                                ['all' => [
                                    ['===' => [1, 1]],
                                    ['===' => [1, 2]],
                                    ['===' => [1, 1]]
                                ]],
                                ['any' => [
                                    ['===' => [1, 2]],
                                    ['===' => [1, 1]],
                                    ['===' => [1, 4]]
                                ]]
                            ]],
                            ['===' => [1, 1]]
                        ]]
                    ]]
                ])
                ->return(false)
        );
    }

    public function testCanIfThen() {
        $this->assertEquals(
            'yes',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['===' => [1, 1]],
                        'yes'
                    ]]
                ])
                ->return('default')
        );
    }

    public function testCanIfThenElse() {
        $this->assertEquals(
            'no',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['===' => [1, 2]],
                        'yes',
                        'no'
                    ]]
                ])
                ->return('default')
        );
    }

    public function testCanIfThenElseAlways() {
        $this->assertEquals(
            'always',
            Mossengine\FiveCode\FiveCode::make()
                ->evaluate([
                    ['if' => [
                        ['===' => [1, 2]],
                        'yes',
                        'no',
                        'always'
                    ]]
                ])
                ->return('default')
        );
    }

}