<?php

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\NumberFilter;
use Psi\Component\Grid\Filter\NumberFilterData;
use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;

class NumberFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new NumberFilter();
    }

    public function testFilter(): FilterDataInterface
    {
        $data = $this->submitFilter([], []);
        $this->assertInstanceOf(NumberFilterData::class, $data);

        return $data;
    }

    /**
     * It should submit an empty array.
     */
    public function testExpressionEmpty()
    {
        $data = $this->submitFilter([], []);
        $expression = $this->getExpression($data);
        $this->assertInstanceOf(Comparison::class, $expression);
    }

    /**
     * It should create the correct comparison.
     *
     * @dataProvider provideTestExpression
     */
    public function testExpression($comparator, $value)
    {
        $data = $this->submitFilter([], [
            'comparator' => $comparator,
            'value' => $value,
        ]);
        $expression = $this->getExpression($data);
        $this->assertInstanceOf(Comparison::class, $expression);
        $this->assertEquals($comparator, $expression->getComparator());
        $this->assertEquals($value, $expression->getValue());
    }

    public function provideTestExpression()
    {
        return [
            [
                Comparison::EQUALS,
                10,
            ],
            [
                Comparison::NOT_EQUALS,
                10,
            ],
            [
                Comparison::GREATER_THAN,
                10,
            ],
            [
                Comparison::GREATER_THAN_EQUAL,
                10,
            ],
            [
                Comparison::LESS_THAN,
                10,
            ],
            [
                Comparison::LESS_THAN_EQUAL,
                10,
            ],
        ];
    }
}