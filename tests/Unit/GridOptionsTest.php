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

    /**
     * It should normalize orderings to lowercase.
     */
    public function testNormalizeOrderings()
    {
        $options = $this->create([
            'orderings' => [
                'foo' => 'ASC',
                'bar' => 'DESC',
            ],
        ]);
        $this->assertEquals([
            'foo' => 'asc',
            'bar' => 'desc',
        ], $options->getOrderings());
    }

    /**
     * It should throw an exception if an invalid ordering is given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Order must be either "asc" or "desc" got "barbar"
     */
    public function testInvalidOrdering()
    {
        $this->create([
            'orderings' => [
                'foo' => 'barbar',
                'bar' => 'DESC',
            ],
        ]);
    }

    /**
     * It should return the parameters required for the filter URL action.
     */
    public function testGetFilterActionUrlOptions()
    {
        $options = $this->create([
            'orderings' => [
                'foo' => 'asc',
            ],
            'page_size' => 10,
            'filter' => [
                'title' => [
                    'comparator' => 'equal',
                    'value' => 'foobar',
                ],
            ],
        ]);
        $this->assertArrayNotHasKey('filter', $options->getFilterActionOptions());
    }

    /**
     * It should return the options as an array.
     */
    public function testGetOptionsAsArray()
    {
        $options = $this->create($asArray = [
            'orderings' => [
                'foo' => 'asc',
            ],
            'page_size' => 10,
            'filter' => [
                'title' => [
                    'comparator' => 'equal',
                    'value' => 'foobar',
                ],
            ],
        ]);
        $this->assertEquals(
            [
                'page_size' => 10,
                'current_page' => 0,
                'orderings' => ['foo' => 'asc'],
                'filter' => ['title' => ['comparator' => 'equal', 'value' => 'foobar']],
                'variant' => null,
            ],
            $options->getOptions()
        );
    }

    private function create(array $options)
    {
        return new GridOptions($options);
    }
}
