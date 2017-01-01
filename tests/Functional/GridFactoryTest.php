<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Functional;

use Psi\Component\Grid as Grid;
use Psi\Component\Grid\GridFactoryBuilder;
use Psi\Component\Grid\Tests\Model\Article;
use Psi\Component\Grid\View\Header;
use Psi\Component\Grid\View\Row;

class GridFactoryTest extends GridTestCase
{
    /**
     * It should throw an exception if a non-mapped class is requested.
     */
    public function testNoMetadata()
    {
        try {
            $this->create()->createGrid(\stdClass::class, []);
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
                'select' => [
                    'type' => 'select',
                ],
                'title' => [
                    'type' => 'property',
                ],
            ],
            'filters' => [
                'title' => [
                    'type' => Grid\Filter\StringFilter::class,
                ],
                'title_two' => [
                    'type' => Grid\Filter\StringFilter::class,
                    'field' => 'title',
                ],
                'number_min' => [
                    'type' => Grid\Filter\NumberFilter::class,
                    'field' => 'number',
                ],
                'number_max' => [
                    'type' => Grid\Filter\NumberFilter::class,
                    'field' => 'number',
                ],
            ],
        ]);
        $grid = $factory->createGrid(Article::class, [
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

        $view = $grid->createView();

        $this->assertCount(3, $view->getTable()->getBody());
        $table = iterator_to_array($view->getTable()->getBody());
        $row = $table[0];
        $this->assertInstanceOf(Row::class, $row);
        $cells = iterator_to_array($row);
        $this->assertEquals('four', $cells['title']->value);
    }

    /**
     * It should throw an exception when creating a view with invalid form.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid filter form: [title]
     */
    public function testFilterDataInvalid()
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
        $grid = $factory->createGrid(Article::class, [
            'filter' => [
                'title' => new \stdClass(),
            ],
        ]);
        $grid->createView();
    }

    /**
     * It should perform actions.
     */
    public function testPerformAction()
    {
        $factory = $this->create([
            'columns' => [
                'select' => [
                    'type' => 'select',
                ],
                'title' => [
                    'type' => 'property',
                ],
            ],
            'actions' => [
                'delete' => [
                    'type' => 'delete',
                ],
            ],
        ]);
        $grid = $factory->createGrid(Article::class, []);

        $grid->performAction('delete', [10, 20]);
        $view = $grid->createView();
        $this->assertCount(2, $view->getTable()->getBody());
        $headers = $view->getTable()->getHeaders();
        $this->assertCount(2, $headers);
        $this->assertContainsOnlyInstancesOf(Header::class, $headers);
    }

    /**
     * It should build types with parent types.
     */
    public function testInheritedType()
    {
        $factory = $this->create([
            'columns' => [
                'the_date' => [
                    'type' => 'datetime',
                    'options' => [
                        'property' => 'date',
                    ],
                ],
            ],
        ]);
        $grid = $factory->createGrid(Article::class, []);
        $view = $grid->createView();
        $rows = iterator_to_array($view->getTable()->getBody());
        $row = reset($rows);
        $cells = iterator_to_array($row);
        $this->assertCount(1, $cells);
        $cell = reset($cells);
        $this->assertEquals('DateTime', $cell->getTemplate());
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
        $grid = $factory->createGrid(Article::class, [
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

    public function testQueries()
    {
        $factory = $this->create([
            'query' => 'default',
            'columns' => [
                'title' => [
                    'type' => 'property',
                ],
            ],
        ], [
            'default' => [
                'criteria' => ['in' => ['title' => ['one', 'two']]],
            ],
        ]);

        $grid = $factory->createGrid(Article::class);
        $view = $grid->createView();
        $table = iterator_to_array($view->getTable()->getBody());
        $this->assertCount(2, $table);
    }

    public function testQueryWithFilter()
    {
        $factory = $this->create([
            'query' => 'default',
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
        ], [
            'default' => [
                'criteria' => ['in' => ['title' => ['one', 'two']]],
            ],
        ]);
        $grid = $factory->createGrid(Article::class, [
            'filter' => [
                'title' => [
                    'comparator' => 'equal',
                    'value' => 'one',
                ],
            ],
        ]);

        $view = $grid->createView();
        $table = iterator_to_array($view->getTable()->getBody());
        $this->assertCount(1, $table);
    }

    private function create(array $gridMapping = [], array $queries = [])
    {
        $container = $this->createContainer([
            'collections' => [
                Article::class => [
                    10 => new Article('one', 1),
                    20 => new Article('two', 2),
                    30 => new Article('three', 3),
                    40 => new Article('four', 4),
                ],
            ],
        ]);

        return GridFactoryBuilder::createWithDefaults($container->get('object_agent.finder'))
            ->addArrayDriver([
                Article::class => [
                    'queries' => $queries,
                    'grids' => [
                        'main' => $gridMapping,
                    ],
                ],
            ])
            ->createGridFactory();
    }
}
