<?php

namespace Psi\Component\Grid\Tests\Util;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\ColumnMetadata;

class MetadataUtil
{
    public static function createGrid($name, array $config)
    {
        $config = array_merge([
            'columns' => [],
            'filters' => [],
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
                'type' => 'test_filter',
                'options' => [],
            ], $filtConfig);

            $filters[] = new FilterMetadata(
                $name,
                $filtConfig['type'],
                $filtConfig['options']
            );
        }

        return new GridMetadata($name, $columns, $filters, 50);
    }
}
