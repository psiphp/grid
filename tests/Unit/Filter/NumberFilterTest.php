<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\NumberFilter;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class NumberFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new NumberFilter();
    }

    public function testFilter()
    {
        $data = $this->submitFilter([], []);

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
        $data = $this->submitFilter([
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

    public function testApplicability()
    {
        $data = [
            'value' => '1',
        ];
        $this->assertTrue($this->getFilter()->isApplicable($data));
        $data = [
            'value' => null,
        ];
        $this->assertFalse($this->getFilter()->isApplicable($data));
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
