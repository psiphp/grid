<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Driver;

use Metadata\Driver\AdvancedDriverInterface;
use Psi\Component\Grid\Metadata\ActionMetadata;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Metadata\QueryMetadata;

class ArrayDriver implements AdvancedDriverInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        if (!isset($this->config[$class->getName()])) {
            return;
        }

        $config = $this->resolveConfig([
            'grids' => [],
            'queries' => [],
        ], $this->config[$class->getName()]);

        $queries = [];
        foreach ($config['queries'] as $queryName => $queryConfig) {
            $queryConfig = $this->resolveConfig([
                'name' => null,
                'selects' => [],
                'joins' => [],
                'criteria' => [],
            ], $queryConfig);
            $queries[$queryName] = new QueryMetadata(
                $queryName, $queryConfig['selects'], $queryConfig['joins'], $queryConfig['criteria']
            );
        }

        $grids = [];
        foreach ($config['grids'] as $gridName => $gridConfig) {
            $gridConfig = $this->resolveConfig([
                'name' => null,
                'query' => null,
                'columns' => [],
                'filters' => [],
                'actions' => [],
                'page_size' => 50,
            ], $gridConfig);

            $columns = [];
            foreach ($gridConfig['columns'] as $columnName => $columnConfig) {
                $columnConfig = $this->resolveConfig([
                    'type' => null,
                    'options' => [],
                ], $columnConfig);

                $columns[$columnName] = new ColumnMetadata(
                    $columnName,
                    $columnConfig['type'],
                    $columnConfig['options']
                );
            }

            $filters = [];
            foreach ($gridConfig['filters'] as $filterName => $filterConfig) {
                $filterConfig = $this->resolveConfig([
                    'type' => null,
                    'field' => null,
                    'options' => [],
                ], $filterConfig);

                $filters[$filterName] = new FilterMetadata(
                    $filterName,
                    $filterConfig['type'],
                    $filterConfig['field'],
                    $filterConfig['options']
                );
            }

            $actions = [];
            foreach ($gridConfig['actions'] as $actionName => $actionConfig) {
                $actionConfig = $this->resolveConfig([
                    'type' => null,
                    'options' => [],
                ], $actionConfig);

                $actions[$actionName] = new ActionMetadata(
                    $actionName,
                    $actionConfig['type'],
                    $actionConfig['options']
                );
            }

            $grids[$gridName] = new GridMetadata(
                $gridName,
                $columns,
                $filters,
                $actions,
                $gridConfig['page_size'],
                $gridConfig['query']
            );
        }

        $classMetadata = new ClassMetadata($class->getName(), $grids, $queries);

        return $classMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllClassNames()
    {
        return array_keys($this->config);
    }

    private function resolveConfig(array $defaultConfig, array $config)
    {
        if ($diff = array_diff(array_keys($config), array_keys($defaultConfig))) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid configuration keys "%s" for grid, valid keys: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaultConfig))
            ));
        }

        return array_merge($defaultConfig, $config);
    }
}
