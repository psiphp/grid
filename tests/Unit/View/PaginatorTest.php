<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\View\Paginator;

class PaginatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should get the last page number.
     */
    public function testNumberOfPages()
    {
        $this->assertEquals(10, $this->create(10, 1, 10, 100)->getNumberOfPages());
        $this->assertEquals(13, $this->create(10, 1, 10, 122)->getNumberOfPages());
    }

    /**
     * It should return true if the current page is the last.
     */
    public function testIsNumberOfPages()
    {
        $this->assertTrue($this->create(10, 3, 10, 30)->isLastPage());
        $this->assertTrue($this->create(10, 3, 7, 27)->isLastPage());
        $this->assertFalse($this->create(10, 2, 7, 27)->isLastPage());
    }

    /**
     * It should throw an exception if trying to determine the last page when the
     * total number of pages is not known.
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot determine
     */
    public function testGetNumberOfPagesIncalculable()
    {
        $this->create(10, 10, 10, null)->getNumberOfPages();
    }

    /**
     * It should retrun the URL parameters for a given page.
     */
    public function testGetUrlParameters()
    {
        $params = $this->create(10, 10, 10, null)->getUrlParametersForPage();
        $this->assertArrayHasKey('page', $params);
        $this->assertEquals(10, $params['page']);

        $params = $this->create(10, 10, 10, null)->getUrlParametersForPage(4);
        $this->assertEquals(4, $params['page']);
    }

    /**
     * It should guess that we are on the last page if we do not know the total
     * number of records and the number of displayed records are less than the page size.
     */
    public function testIsNumberOfPagesUnknownNumberOfRecords()
    {
        $this->assertTrue($this->create(10, 1, 9, null)->isLastPage());

        // but we do not know that it's the last page if the number of dispayed pages
        // is divisible by the page size...
        $this->assertFalse($this->create(10, 1, 10, null)->isLastPage());
    }

    public function create(int $pageSize, int $currentPage, int $numberOfRecordsOnPage, int $numberOfRecords = null)
    {
        return new Paginator(new GridContext(\stdClass::class, [
            'page_size' => $pageSize,
            'page' => $currentPage,
        ]), $numberOfRecordsOnPage, $numberOfRecords);
    }
}
