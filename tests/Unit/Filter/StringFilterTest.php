<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\StringFilter;
use Psi\Component\Grid\Filter\StringFilterData;
use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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

    public function testApplicability()
    {
        $data = $this->submitFilter([], [
            'value' => '1',
        ]);
        $this->assertTrue($data->isApplicable());
        $data = $this->submitFilter([], [
            'value' => null,
        ]);
        $this->assertFalse($data->isApplicable());
    }

    /**
     * It should hide the comparator select if only one comparator is present.'.
     */
    public function testHideSelect()
    {
        $form = $this->createForm([
            'comparators' => ['equal'],
        ]);
        $this->assertInstanceOf(HiddenType::class, $form->get('comparator')->getConfig()->getType()->getInnerType());

        $form = $this->createForm([
            'comparators' => ['equal', 'contains'],
        ]);
        $this->assertInstanceOf(ChoiceType::class, $form->get('comparator')->getConfig()->getType()->getInnerType());
    }
}
