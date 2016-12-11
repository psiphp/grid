<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Benchmarks;

use Psi\Component\Grid\Tests\Functional\GridTestCase;
use Psi\Component\Grid\Tests\Model\Article;

/**
 * @BeforeMethods({"init"})
 * @Revs(100)
 * @Iterations(10)
 * @OutputTimeUnit("milliseconds")
 * @ParamProviders({"provideNbObjects"})
 */
class GridBench extends GridTestCase
{
    private $factory;

    public function init($params)
    {
        $nbObjects = $params['nb_objects'];

        $objects = [];
        for ($i = 0; $i < $nbObjects; $i++) {
            $objects[] = new Article('hello', $i);
        }

        $container = $this->createContainer([
            'mapping' => [
                Article::class => [
                    'grids' => [
                        'main' => [
                            'columns' => [
                                'title' => [
                                    'type' => 'property',
                                ],
                                'number' => [
                                    'type' => 'property',
                                ],
                            ],
                            'filters' => [
                                'title' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                        'form' => [
                            'columns' => [
                                'select' => [
                                    'type' => 'select',
                                ],
                                'title' => [
                                    'type' => 'property',
                                ],
                                'number' => [
                                    'type' => 'property',
                                ],
                            ],
                            'filters' => [
                                'title' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'collections' => [
                Article::class => $objects,
            ],
        ]);

        $this->factory = $container->get('grid.factory');
    }

    /**
     * @Subject()
     */
    public function grid_loading()
    {
        $this->factory->createGrid(Article::class, []);
    }

    /**
     * @Subject()
     */
    public function grid_loading_with_form()
    {
        $this->factory->createGrid(Article::class, [
            'variant' => 'form',
        ]);
    }

    public function provideNbObjects()
    {
        return [
            [
                'nb_objects' => 10,
            ],
            [
                'nb_objects' => 100,
            ],
            [
                'nb_objects' => 1000,
            ],
        ];
    }
}
