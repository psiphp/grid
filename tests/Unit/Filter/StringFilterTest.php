<?php

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\StringFilter;
use Psi\Component\Grid\Filter\StringFilterData;
use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;

class StringFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new StringFilter();
    }

    public function testFilter(): FilterDataInterface
    {
        $data = $this->submitFilter([], []);
        $this->assertInstanceOf(StringFilterData::class, $data);

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
    public function testExpression($comparator, $value, $expectedComparator, $expectedValue)
    {
        $data = $this->submitFilter([], [
            'comparator' => $comparator,
            'value' => $value,
        ]);
        $expression = $this->getExpression($data);
        $this->assertInstanceOf(Comparison::class, $expression);
        $this->assertEquals($expectedComparator, $expression->getComparator());
        $this->assertEquals($expectedValue, $expression->getValue());
    }

    public function provideTestExpression()
    {
        return [
            [
                StringFilter::TYPE_EQUAL,
                10,
                'eq',
                10,
            ],
            [
                StringFilter::TYPE_EMPTY,

                null,
                'null',
                null,
            ],
            [
                StringFilter::TYPE_NOT_EMPTY,
                10,
                'not_null',
                null,
            ],
            [
                StringFilter::TYPE_CONTAINS,
                10,
                'contains',
                '%10%',
            ],
            [
                StringFilter::TYPE_NOT_CONTAINS,
                10,
                'not_contains',
                '%10%',
            ],
            [
                StringFilter::TYPE_STARTS_WITH,
                10,
                'contains',
                '10%',
            ],
            [
                StringFilter::TYPE_ENDS_WITH,
                10,
                'contains',
                '%10',
            ],
            [
                StringFilter::TYPE_IN,
                '10, 20, 30',
                'in',
                ['10', '20', '30'],
            ],
            [
                StringFilter::TYPE_NOT_IN,
                10,
                'nin',
                ['10'],
            ],
        ];
    }
}
