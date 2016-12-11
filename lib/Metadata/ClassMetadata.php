<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

use Metadata\MergeableClassMetadata;

final class ClassMetadata extends MergeableClassMetadata
{
    private $grids;
    private $queries;

    public function __construct($name, array $grids, array $queries = [])
    {
        parent::__construct($name);

        array_map(function (GridMetadata $grid) {
            $grid->attachClassMetadata($this);
        }, $grids);
        array_map(function (QueryMetadata $query) {
        }, $queries);

        $this->grids = $grids;
        $this->queries = $queries;
    }

    public function getGrids(): array
    {
        return $this->grids;
    }

    public function getQueries()
    {
        return $this->queries;
    }
}
