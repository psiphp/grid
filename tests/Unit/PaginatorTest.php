<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should get the last page number.
     */
    public function testLastPage()
    {
        $this->assertEquals(10, $this->create(10, 1, 100)->getLastPage());
        $this->assertEquals(13, $this->create(10, 1, 122)->getLastPage());
    }

    public function create(int $pageSize, int $currentPage, int $numberOfRecords)
    {
        return new Paginator($pageSize, $currentPage, $numberOfRecords);
    }
}
