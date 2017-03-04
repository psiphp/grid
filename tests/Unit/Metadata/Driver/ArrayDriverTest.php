<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Metadata\Driver;

use Metadata\Driver\DriverInterface;
use Psi\Component\Grid\Metadata\Driver\ArrayDriver;
use Psi\Component\Grid\Tests\Model\Article;

class ArrayDriverTest extends DriverTestCase
{
    public function getDriver(): DriverInterface
    {
        return new ArrayDriver([
            Article::class => [
                'grids' => [
                    'foobar' => [
                        'columns' => [
                            'title' => [
                                'type' => 'property_value',
                                'options' => ['property' => 'title'],
                                'tags' => ['tag1'],
                            ],
                            'foo' => [
                                'type' => 'expression',
                                'options' => ['expr' => 'object.foo'],
                                'tags' => ['tag1'],
                            ],
                        ],
                        'filters' => [
                            'foobar' => [
                                'field' => 'title',
                                'type' => 'text',
                                'tags' => ['tag1'],
                                'options' => [],
                            ],
                        ],
                        'actions' => [
                            'delete_selected' => [
                                'type' => 'delete',
                                'tags' => ['tag1'],
                            ],
                        ],
                    ],
                    'barfoo' => [
                    ],
                ],
            ],
        ]);
    }

    /**
     * It should throw an exception if there are invalid keys on the grid.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid configuration keys "bo" for grid, valid keys:
     */
    public function testInvalidKeysGrid()
    {
        $this->loadMetadata([
            Article::class => [
                'grids' => [
                    'barbar' => [
                        'bo' => [],
                    ],
                ],
            ],
        ]);
    }

    private function loadMetadata(array $config)
    {
        $driver = new ArrayDriver($config);

        return $driver->loadMetadataForClass(new \ReflectionClass(Article::class));
    }
}
