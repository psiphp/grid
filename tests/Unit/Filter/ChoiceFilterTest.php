<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\ChoiceFilter;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;

class ChoiceFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new ChoiceFilter();
    }

    public function testFilter()
    {
        $data = $this->submitFilter([
            'value' => 'One',
        ], [
            'choices' => [
                'one' => 'One',
            ],
        ]);
        $expression = $this->getExpression($data);

        $this->assertInstanceOf(Comparison::class, $expression);
        $this->assertEquals(Comparison::EQUALS, $expression->getComparator());
        $this->assertEquals('test', $expression->getField());
        $this->assertEquals('One', $expression->getValue());
    }

    public function testFilterAllowsMultiple()
    {
        $data = $this->submitFilter([
            'value' => ['One', 'Three'],
        ], [
            'multiple' => true,
            'choices' => [
                'one' => 'One',
                'two' => 'Two',
                'three' => 'Three',
            ],
        ]);
        $expression = $this->getExpression($data);

        $this->assertInstanceOf(Comparison::class, $expression);
        $this->assertEquals(Comparison::IN, $expression->getComparator());
        $this->assertEquals('test', $expression->getField());
        $this->assertEquals(['One', 'Three'], $expression->getValue());
    }
}
