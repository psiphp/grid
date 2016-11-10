<?php

namespace Psi\Component\Grid\Tests\Functional;

use Psi\Component\Grid\Row;
use Psi\Component\Grid\Tests\Model\Article;

class GridTest extends GridTestCase
{
    /**
     * It should create a grid.
     */
    public function testGrid()
    {
        $container = $this->createContainer([
            'mapping' => [
                Article::class => [
                    'grids' => [
                        'main' => [
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
                        ],
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

        $factory = $container->get('grid.factory');

        $grid = $factory->loadGrid(Article::class, [
            'orderings' => [
                'title' => 'asc',
            ],
            'current_page' => 0,
            'page_size' => 10,
            'filter_data' => [
                'title' => [
                    'comparator' => 'in',
                    'value' => 'four, one, two',
                ],
            ]
        ]);

        $this->assertCount(3, $grid->getTable()->getBody());
        $table = iterator_to_array($grid->getTable()->getBody());
        $row = $table[0];
        $this->assertInstanceOf(Row::class, $row);
        $cells = iterator_to_array($row);
        $this->assertEquals('four', $cells['title']->getValue());
    }
}
