<?php

namespace App;

use InvalidArgumentException;

class Calculator
{
    public function __construct(
        protected string $plusSign = '+',
        protected string $minusSign = '-',
        protected string $multiplySign = 'x',
        protected string $divideSign = '/',
        protected string $leftParenthesis = '(',
        protected string $rightParenthesis = ')',
        protected int $maxScale = 10
    ) {
    }

    public function calculate(string $expression): string
    {
        $expression = str_replace(' ', '', $expression);

        $this->validateExpression($expression);

        $expression = "($expression)";

        $result = $this->runParenthesis($expression);

        // remove trailing zeros, if any and remove trailing dot
        $result = rtrim((strpos($result,".") !== false ? rtrim($result, "0") : $result),".");

        return empty($result) ? '0' : $result;
    }

    protected function validateExpression(string $expression): void
    {
        if (empty($expression)) {
            throw new InvalidArgumentException('Empty expression');
        }

        substr_count($expression, $this->leftParenthesis) !== substr_count($expression, $this->rightParenthesis)
            && throw new InvalidArgumentException("Parenthesis don't match");

        //check if expression contains only valid characters
        $validCharacters = array_merge(
            range('0', '9'),
            ['.', $this->plusSign, $this->minusSign, $this->multiplySign, $this->divideSign, $this->leftParenthesis, $this->rightParenthesis]
        );

        foreach (str_split($expression) as $char) {
            if (! in_array($char, $validCharacters)) {
                throw new InvalidArgumentException("Invalid character: $char");
            }
        }
    }

    protected function runParenthesis(string $expression): string
    {
        for ($pos = 1; $pos < strlen($expression); $pos++) {
            $char = $expression[$pos];
            if ($char === $this->leftParenthesis) {
                $expression = substr($expression, 0, $pos).$this->runParenthesis(substr($expression, $pos));
                $pos -= 1;
            } elseif ($char === $this->rightParenthesis) {
                $parenthesisExpression = substr($expression, 1, $pos - 1);
                $parenthesisResult = $this->runCalculation($parenthesisExpression);
                $expression = str_replace(
                    '('.$parenthesisExpression.')',
                    $parenthesisResult,
                    $expression
                );

                return $expression;
            }
        }

        return $expression;
    }

    protected function runCalculation(string $expression): string
    {
        $expression = $this->calculateMultiplyAndDivide($expression);

        $expression = $this->calculateAddAndSubtract($expression);

        return $expression;
    }

    protected function calculateMultiplyAndDivide(string $expression): string
    {
        $multiplyPosition = strpos($expression, $this->multiplySign);
        $dividePosition = strpos($expression, $this->divideSign);

        if ($multiplyPosition === false && $dividePosition === false) {
            return $expression;
        }

        if ($multiplyPosition === false) {
            $operatorPosition = $dividePosition;
            $operator = $this->divideSign;
        } elseif ($dividePosition === false) {
            $operatorPosition = $multiplyPosition;
            $operator = $this->multiplySign;
        } elseif ($multiplyPosition < $dividePosition) {
            $operatorPosition = $multiplyPosition;
            $operator = $this->multiplySign;
        } else {
            $operatorPosition = $dividePosition;
            $operator = $this->divideSign;
        }

        $leftNumberPosition = $this->findLeftNumberPosition($expression, $operatorPosition);
        $rightNumberPosition = $this->findRightNumberPosition($expression, $operatorPosition);

        $leftNumber = substr(
            $expression,
            $leftNumberPosition,
            $operatorPosition - $leftNumberPosition
        );

        $rightNumber = substr(
            $expression,
            $operatorPosition + 1,
            $rightNumberPosition - $operatorPosition - 1
        );

        $result = $this->calculateResult($leftNumber, $rightNumber, $operator);

        $expression = str_replace(
            $leftNumber.$operator.$rightNumber,
            $result,
            $expression
        );

        return $this->calculateMultiplyAndDivide($expression);
    }

    protected function calculateAddAndSubtract(string $expression): string
    {
        $plusPosition = strpos($expression, $this->plusSign);
        $minusPosition = strpos($expression, $this->minusSign);

        if (($plusPosition === false && $minusPosition === false) || ($plusPosition === false && $minusPosition === 0)) {
            return $expression;
        }

        if ($plusPosition === false) {
            $operatorPosition = $minusPosition;
            $operator = $this->minusSign;
        } elseif ($minusPosition === false) {
            $operatorPosition = $plusPosition;
            $operator = $this->plusSign;
        } elseif ($plusPosition < $minusPosition) {
            $operatorPosition = $plusPosition;
            $operator = $this->plusSign;
        } else {
            $operatorPosition = $minusPosition;
            $operator = $this->minusSign;
        }

        $leftNumber = $this->findLeftNumber($expression, $operatorPosition);

        $rightNumber = $this->findRightNumber($expression, $operatorPosition);

        $result = $this->calculateResult($leftNumber, $rightNumber, $operator);

        $expression = str_replace(
            $leftNumber.$operator.$rightNumber,
            $result,
            $expression
        );

        return $this->calculateAddAndSubtract($expression);
    }

    protected function findLeftNumber(string $expression, int $operatorPosition): string
    {
        $leftNumberPosition = $this->findLeftNumberPosition($expression, $operatorPosition);

        return substr(
            $expression,
            $leftNumberPosition,
            $operatorPosition - $leftNumberPosition
        );
    }

    protected function findRightNumber(string $expression, int $operatorPosition): string
    {
        $rightNumberPosition = $this->findRightNumberPosition($expression, $operatorPosition);

        return substr(
            $expression,
            $operatorPosition + 1,
            $rightNumberPosition - $operatorPosition - 1
        );
    }

    protected function findLeftNumberPosition(string $expression, int $operatorPosition): int
    {
        if ($operatorPosition === 0) {
            return 0;
        }

        $leftNumberPosition = $operatorPosition - 1;

        while ((is_numeric($expression[$leftNumberPosition]) || $expression[$leftNumberPosition] == '.') && $leftNumberPosition > 0) {
            $leftNumberPosition--;
        }

        if ($leftNumberPosition > 0) {
            $leftNumberPosition++;
        }

        return $leftNumberPosition;
    }

    protected function findRightNumberPosition(string $expression, int $operatorPosition): int
    {
        if ($operatorPosition === strlen($expression) - 1) {
            return strlen($expression);
        }

        $rightNumberPosition = $operatorPosition + 1;

        while ($rightNumberPosition < strlen($expression) && (is_numeric($expression[$rightNumberPosition]) || $expression[$rightNumberPosition] == '.')) {
            $rightNumberPosition++;
        }

        return $rightNumberPosition;
    }

    protected function calculateResult(string $leftNumber, string $rightNumber, string $operator): string
    {
        $scale = $this->maxScale;

        switch ($operator) {
            case $this->plusSign:
                return bcadd($leftNumber, $rightNumber, $scale);
            case $this->minusSign:
                return bcsub($leftNumber, $rightNumber, $scale);
            case $this->multiplySign:
                return bcmul($leftNumber, $rightNumber, $scale);
            case $this->divideSign:
                return bcdiv($leftNumber, $rightNumber, $scale);
        }
    }
}
