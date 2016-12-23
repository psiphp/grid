<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Util;

use Psi\Component\Grid\Metadata\ActionMetadata;
use Psi\Component\Grid\Metadata\ClassMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;

class MetadataUtil
{
    public static function createClass($className, $grids)
    {
        return new ClassMetadata($className, $grids);
    }

    public static function createGrid($name, array $config)
    {
        $config = array_merge([
            'query' => null,
            'columns' => [],
            'filters' => [],
            'actions' => [],
        ], $config);

        $columns = [];
        foreach ($config['columns'] as $name => $colConfig) {
            $colConfig = array_merge([
                'type' => 'test_type',
                'options' => [],
            ], $colConfig);

            $columns[$name] = new ColumnMetadata(
                $name,
                $colConfig['type'],
                $colConfig['options']
            );
        }

        $filters = [];
        foreach ($config['filters'] as $name => $filtConfig) {
            $filtConfig = array_merge([
                'field' => null,
                'defaults' => [],
                'type' => 'test_filter',
                'options' => [],
            ], $filtConfig);

            $filters[$name] = new FilterMetadata(
                $name,
                $filtConfig['type'],
                $filtConfig['field'],
                $filtConfig['defaults'],
                $filtConfig['options']
            );
        }

        $actions = [];
        foreach ($config['actions'] as $name => $actionConfig) {
            $actionConfig = array_merge([
                'field' => null,
                'options' => [],
            ], $actionConfig);

            $actions[$name] = new ActionMetadata(
                $name,
                $actionConfig['type'],
                $actionConfig['options']
            );
        }

        return new GridMetadata($name, $columns, $filters, $actions, 50, $config['query']);
    }
}
