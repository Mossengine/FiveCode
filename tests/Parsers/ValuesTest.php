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

}