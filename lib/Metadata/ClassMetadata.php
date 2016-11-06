<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

use Metadata\MergeableClassMetadata;

final class ClassMetadata extends MergeableClassMetadata
{
    private $grids;

    public function __construct($name, array $grids)
    {
        parent::__construct($name);
        array_map(function (GridMetadata $grid) {
        }, $grids);

        $this->grids = $grids;
    }

    public function getGrids(): array
    {
        return $this->grids;
    }
}
