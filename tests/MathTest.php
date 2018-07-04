<?php

/**
 * Class FiveCodeTest
 */
class MathTest extends PHPUnit_Framework_TestCase
{
    public function testAddition() {
        $this->assertTrue(floatval(9) === Mossengine\FiveCode\Math::addition(4, 5));
    }

    public function testAdditionMulitple() {
        $this->assertTrue(floatval(13) === Mossengine\FiveCode\Math::addition(4, 5, 4));
    }

    public function testSubtraction() {
        $this->assertTrue(floatval(9) === Mossengine\FiveCode\Math::subtract(13, 4));
    }

    public function testSubtractionMulitple() {
        $this->assertTrue(floatval(4) === Mossengine\FiveCode\Math::subtract(13, 4, 5));
    }

    public function testDivide() {
        $this->assertTrue(floatval(4) === Mossengine\FiveCode\Math::divide(12, 3));
    }

    public function testDivideMulitple() {
        $this->assertTrue(floatval(2) === Mossengine\FiveCode\Math::divide(12, 3, 2));
    }

    public function testMulitply() {
        $this->assertTrue(floatval(144) === Mossengine\FiveCode\Math::multiply(12, 12));
    }

    public function testMulitplyMulitple() {
        $this->assertTrue(floatval(1296) === Mossengine\FiveCode\Math::multiply(12, 12, 9));
    }

    public function testRandom() {
        $intRandom = Mossengine\FiveCode\Math::random(0, 100);
        $this->assertTrue(100 > $intRandom && 0 < $intRandom);
        $intRandom = Mossengine\FiveCode\Math::random(50, 50);
        $this->assertTrue(50 === $intRandom);
        unset($intRandom);
    }
}