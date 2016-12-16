<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\GridContext;

/**
 * TODO: Finish testing this class.
 */
class GridContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return the page offest when no pagination is set.
     */
    public function testPageOffsetNoPagination()
    {
        $context = new GridContext(\stdClass::class, [
            'page_size' => null,
        ]);
        $pageOffset = $context->getPageOffset();
        $this->assertEquals(0, $pageOffset);
    }

    /**
     * It should return the page offset.
     */
    public function testPageOffset()
    {
        $context = new GridContext(\stdClass::class, [
            'page_size' => 10,
            'page' => 2,
        ]);
        $pageOffset = $context->getPageOffset();
        $this->assertEquals(10, $pageOffset);
    }
}
