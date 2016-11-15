<?php

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\View\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
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

    public function create($name, $options)
    {
        return new Header($name, new GridContext(\stdClass::class, $options));
    }
}
