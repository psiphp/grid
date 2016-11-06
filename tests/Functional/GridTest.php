<?php

namespace Psi\Component\Grid\Tests\Functional;

use Metadata\MetadataFactory;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\CollectionsAgent;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\Store;
use Psi\Component\Grid\Cell\PropertyType;
use Psi\Component\Grid\GridFactory;
use Psi\Component\Grid\Metadata\Driver\ArrayDriver;
use Psi\Component\Grid\Row;
use Psi\Component\Grid\Tests\Model\Article;
use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\View\TypeRegistry;
use Psi\Component\View\ViewFactory;

class GridTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should create a grid.
     */
    public function testGrid()
    {
        $driver = new ArrayDriver([
            Article::class => [
                'grids' => [
                    'main' => [
                        'columns' => [
                            'title' => [
                                'type' => 'property',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
        $agentFinder = new AgentFinder([
            'dummy' => new CollectionsAgent(new Store([
                Article::class => [
                    new Article('one'),
                    new Article('two'),
                    new Article('three'),
                    new Article('four'),
                ],
            ])),
        ]);

        $typeRegistry = new TypeRegistry();
        $typeRegistry->register('property', new PropertyType());
        $viewFactory = new ViewFactory($typeRegistry);
        $metadataFactory = new MetadataFactory($driver);

        $factory = new GridFactory(
            $agentFinder,
            $metadataFactory,
            $viewFactory
        );

        $grid = $factory->loadGrid(Article::class, [
            'orderings' => [
                'title' => 'asc',
            ],
            'page' => 0,
            'page_size' => 10,
        ]);

        $this->assertCount(4, $grid);
        $grid = iterator_to_array($grid);
        $row = $grid[0];
        $this->assertInstanceOf(Row::class, $row);
        $cells = iterator_to_array($row);
        $this->assertEquals('four', $cells['title']->getValue());
    }
}
