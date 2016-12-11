<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\Filter\BooleanFilter;
use Psi\Component\Grid\Filter\BooleanFilterData;
use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;

class BooleanFilterTest extends FilterTestCase
{
    protected function getFilter(): FilterInterface
    {
        return new BooleanFilter();
    }

    public function testFilter(): FilterDataInterface
    {
        $data = $this->submitFilter([], []);
        $this->assertInstanceOf(BooleanFilterData::class, $data);

        return $data;
    }

    public function testExpession()
    {
        $data = $this->submitFilter([], []);
        $expression = $this->getExpression($data);
        $this->assertInstanceOf(Comparison::class, $expression);
    }
}
