<?php

namespace Psi\Component\Grid\Tests\Functional;

use Psi\Component\Grid\Row;
use Psi\Component\Grid\Tests\Model\Article;

class GridFactoryTest extends GridTestCase
{
    /**
     * It should throw an exception if a non-mapped class is requested.
     */
    public function testNoMetadata()
    {
        try {
            $this->create()->loadGrid(\stdClass::class, []);
            $this->fail('Did not throw an exception');
        } catch (\Exception $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf(\InvalidArgumentException::class, $e);
            $this->assertEquals('Could not locate grid metadata', $previous->getMessage());
        }
    }

    /**
     * It should create a grid.
     */
    public function testGrid()
    {
        $factory = $this->create([
            'columns' => [
                'title' => [
                    'type' => 'property',
                ],
            ],
            'filters' => [
                'title' => [
                    'type' => 'string',
                ],
                'title_two' => [
                    'type' => 'string',
                    'field' => 'title',
                ],
                'number_min' => [
                    'type' => 'number',
                    'field' => 'number',
                ],
                'number_max' => [
                    'type' => 'number',
                    'field' => 'number',
                ],
            ],
        ]);
        $grid = $factory->loadGrid(Article::class, [
            'orderings' => [
                'title' => 'asc',
            ],
            'page' => 0,
            'page_size' => 10,
            'filter' => [
                'title' => [
                    'comparator' => 'in',
                    'value' => 'four, one, two',
                ],
            ],
        ]);

        $this->assertCount(3, $grid->getTable()->getBody());
        $table = iterator_to_array($grid->getTable()->getBody());
        $row = $table[0];
        $this->assertInstanceOf(Row::class, $row);
        $cells = iterator_to_array($row);
        $this->assertEquals('four', $cells['title']->getValue());
    }

    /**
     * It should bind the form-filter data.
     *
     * @dataProvider provideFilterData
     */
    public function testFilterData(array $filterData)
    {
        $factory = $this->create([
            'columns' => [
                'title' => [
                    'type' => 'property',
                ],
            ],
            'filters' => [
                'title' => [
                    'type' => 'string',
                ],
            ],
        ]);
        $grid = $factory->loadGrid(Article::class, [
            'filter' => $filterData,
        ]);
    }

    public function provideFilterData()
    {
        return [
            'empty_values' => [
                [
                    'title' => [
                        'comparator' => 'equal',
                        'value' => '',
                    ],
                ],
            ],
        ];
    }

    private function create(array $gridMapping = [])
    {
        $container = $this->createContainer([
            'mapping' => [
                Article::class => [
                    'grids' => [
                        'main' => $gridMapping,
                    ],
                ],
            ],
            'collections' => [
                Article::class => [
                    new Article('one', 1),
                    new Article('two', 2),
                    new Article('three', 3),
                    new Article('four', 4),
                ],
            ],
        ]);

        return $container->get('grid.factory');
    }
}
