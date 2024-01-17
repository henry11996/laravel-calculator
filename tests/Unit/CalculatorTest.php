<?php

namespace Tests\Unit;

use App\Calculator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function test_calculator($expect, $expression): void
    {
        $calculator = (new Calculator());

        $this->assertEquals($expect, $calculator->calculate($expression));
    }

    public static function validDataProvider()
    {
        return [
            [2, '1+1'],
            [1, '2/2'],
            [7, '1 + 2 x 3'],
            [9, '( 1 + 2 ) x 3'],
            [0, '()'],
            [0, '(0)'],
            [0, '0/1'],
            [21, '((1+2)x(3+4))'],
            [11, '1+2x3+4'],
            [1000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, '1+999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999'],
            [2.5555555555, '1+1.5555555555'],
            [3, '1+1+1'],
            [1, '1-1+1'],
            [2, '1x1+1'],
            [3, '1+1x2'],
            [2, '1+2/2'],
            [4, '1+2x2-1'],
            [3, '1+2x2-2'],
            [9, '(1+2)x3'],
            [21, '(1+2)x(3+4)'],
            [1, '1+++'],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_invalid_expression($exception, $expression): void
    {
        $calculator = (new Calculator());

        $this->expectException($exception);

        $calculator->calculate($expression);
    }

    public static function invalidDataProvider()
    {
        return [
            [InvalidArgumentException::class,  '(1+2'],
            [InvalidArgumentException::class,  '1+2)'],
            [InvalidArgumentException::class,  'a'],
            [InvalidArgumentException::class,  '1+a'],
            [InvalidArgumentException::class,  '1+1a'],
        ];
    }
}
