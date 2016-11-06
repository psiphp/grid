<?php

namespace Psi\Component\Grid\Metadata;

use Metadata\MergeableClassMetadata;

class ClassMetadata extends MergeableClassMetadata
{
    private $grids;

    public function __construct($name, array $grids)
    {
        parent::__construct($name);
        array_map(function (GridMetadata $grid) {
        }, $grids);

        $this->grids = $grids;
    }

    public function getGrids()
    {
        return $this->grids;
    }
}
