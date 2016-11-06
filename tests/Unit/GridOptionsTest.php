<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\GridOptions;

class GridOptionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should throw an exception if unknown options are given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid grid options "bar". Valid options:
     */
    public function testInvalidOptions()
    {
        $this->create([
            'bar' => 'boo',
        ]);
    }

    /**
     * It should return the page offset.
     *
     * @dataProvider providePageOffset
     */
    public function testPageOffset(array $options, int $expectedOffset)
    {
        $options = $this->create($options);
        $this->assertEquals($expectedOffset, $options->getPageOffset());
    }

    public function providePageOffset()
    {
        return [
            [
                [
                ],
                0,
            ],
            [
                [
                    'page_size' => 10,
                    'current_page' => 0,
                ],
                0,
            ],
            [
                [
                    'page_size' => 10,
                    'current_page' => 1,
                ],
                10,
            ],
            [
                [
                    'page_size' => 10,
                    'current_page' => 20,
                ],
                200,
            ],
        ];
    }

    private function create(array $options)
    {
        return new GridOptions($options);
    }
}
