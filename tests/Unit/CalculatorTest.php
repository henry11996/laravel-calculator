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
            [0.3333333333, '1/3'],
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
            [3, '2+1'],
            [4, '5-1'],
            [24, '4x6'],
            [4, '8/2'],
            [20, '(3+7)x2'],
            [1, '9-(4x2)'],
            [8, '(5+3)x(2-1)'],
            [12, '4x(6/2)'],
            [9, '0+9'],
            [-15, '(-5)x3'],
            [3.5, '2.5+1'],
            [3.33, '5-1.67'],
            [15.75, '2.5x6.3'],
            [4.2, '8.4/2'],
            [20, '(3+7)x2'],
            [1, '9-(4x2)'],
            [8, '(5+3)x(2-1)'],
            [12, '4x(6/2)'],
            [9, '0+9'],
            [-15.75, '(-5.25)x3'],
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
