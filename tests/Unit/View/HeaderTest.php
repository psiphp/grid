<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\View\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return the sort field.
     */
    public function testReturnSortField()
    {
        $header = $this->create('foobar', [], 'barbar');
        $this->assertEquals('barbar', $header->getSortField());
    }

    /**
     * It should return true if it is sorted.
     */
    public function testIsSorted()
    {
        $header = $this->create('foobar', [
            'orderings' => ['barfoo' => 'desc'],
        ]);
        $this->assertFalse($header->isSorted());

        $header = $this->create('foobar', [
            'orderings' => ['foobar' => 'desc'],
        ]);
        $this->assertTrue($header->isSorted());
    }

    /**
     * It should return true if the sort is ascending.
     */
    public function testIsSortAscending()
    {
        $header = $this->create('foobar', [
            'orderings' => ['foobar' => 'desc'],
        ]);
        $this->assertFalse($header->isSortAscending());

        $header = $this->create('foobar', [
            'orderings' => ['foobar' => 'asc'],
        ]);
        $this->assertTrue($header->isSortAscending());
    }

    /**
     * It should return the URL parameters for a given sort order.
     */
    public function testSortParameters()
    {
        $header = $this->create('foobar', []);
        $urlParams = $header->getUrlParametersForSort();
        $this->assertArrayHasKey('orderings', $urlParams);
        $this->assertArrayHasKey('foobar', $urlParams['orderings']);
        $this->assertEquals('asc', $urlParams['orderings']['foobar']);

        $urlParams = $header->getUrlParametersForSort('desc');
        $this->assertEquals('desc', $urlParams['orderings']['foobar']);
    }

    /**
     * It should throw an exception if trying to determine sort direction when field is not sorted.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot determine if sort is ascending when the field ("foobar") is not sorted
     */
    public function testIsSortAscendingException()
    {
        $header = $this->create('foobar', [
            'orderings' => ['barfoo' => 'asc'],
        ]);
        $header->isSortAscending();
    }

    /**
     * It should return true if the header *can* be sorted.
     * It should return false if the header *cannot* be sorted.
     */
    public function testCanBeSorted()
    {
        $header = $this->create('foobar', [], 'barbar');
        $this->assertTrue($header->canBeSorted());

        $header = $this->create('foobar', []);
        $this->assertFalse($header->canBeSorted());
    }

    public function create($name, $options, $sortField = null, $template = 'Foobar')
    {
        return new Header(new GridContext(\stdClass::class, $options), $name, $name, $template, $sortField);
    }
}
