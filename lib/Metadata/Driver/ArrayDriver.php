<?php

namespace Psi\Component\Grid\Metadata\Driver;

use Metadata\Driver\AdvancedDriverInterface;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;

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
        ], $this->config[$class->getName()]);

        $grids = [];

        foreach ($config['grids'] as $gridName => $gridConfig) {
            $gridConfig = $this->resolveConfig([
                'name' => null,
                'columns' => [],
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

            $grids[$gridName] = new GridMetadata(
                $gridName,
                $columns,
                $gridConfig['page_size']
            );
        }

        $classMetadata = new ClassMetadata($class->getName(), $grids);

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
