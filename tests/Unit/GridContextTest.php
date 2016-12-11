<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\GridContext;

class GridContextTest extends \PHPUnit_Framework_TestCase
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
     * It should have getters.
     */
    public function testGetters()
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

        $this->assertEquals(10, $options->getPageSize());
        $this->assertEquals(\stdClass::class, $options->getClassFqn());
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
                    'page' => 0,
                ],
                0,
            ],
            [
                [
                    'page_size' => 10,
                    'page' => 1,
                ],
                0,
            ],
            [
                [
                    'page_size' => 10,
                    'page' => 2,
                ],
                10,
            ],
            [
                [
                    'page_size' => 10,
                    'page' => 20,
                ],
                190,
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
                'page' => 1,
                'orderings' => ['foo' => 'asc'],
                'filter' => ['title' => ['comparator' => 'equal', 'value' => 'foobar']],
                'variant' => null,
                'class' => 'stdClass',
            ],
            $options->getUrlParameters()
        );
    }

    private function create(array $options)
    {
        return new GridContext(\stdClass::class, $options);
    }
}
