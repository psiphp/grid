<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\DateFilter;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class DateFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new DateFilter();
    }

    public function testFilter()
    {
        $this->submitFilter([
            'apply' => 1,
            'comparator' => Comparison::EQUALS,
            'value' => [
                'year' => '2016',
                'month' => '12',
                'day' => '16',
            ],
        ]);
    }

    /**
     * It should create the correct comparison.
     *
     * @dataProvider provideTestExpression
     */
    public function testExpression($comparator, $value, $expectedValue)
    {
        $data = $this->submitFilter([
            'apply' => 1,
            'comparator' => $comparator,
            'value' => $value,
        ]);
        $expression = $this->getExpression($data);
        $this->assertInstanceOf(Comparison::class, $expression);
        $this->assertEquals($comparator, $expression->getComparator());
        $this->assertEquals($expectedValue, $expression->getValue());
    }

    public function provideTestExpression()
    {
        return [
            [
                Comparison::EQUALS,
                [
                    'year' => '2016',
                    'month' => '12',
                    'day' => '16',
                ],
                new \DateTime('2016-12-16'),
            ],
            [
                Comparison::LESS_THAN,
                [
                    'year' => '2016',
                    'month' => '12',
                    'day' => '16',
                ],
                new \DateTime('2016-12-16'),
            ],
        ];
    }

    public function testApplicability()
    {
        $data = [
            'apply' => true,
        ];
        $this->assertTrue($this->getFilter()->isApplicable($data));
        $data = [
            'apply' => false,
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
