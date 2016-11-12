<?php

namespace Psi\Component\Grid\Form;

use Psi\Component\Grid\CellRegistry;
use Psi\Component\Grid\FilterRegistry;
use Symfony\Component\Form\AbstractExtension;

class GridExtension extends AbstractExtension
{
    private $cellRegistry;
    private $filterRegistry;

    public function __construct(CellRegistry $cellRegistry, FilterRegistry $filterRegistry)
    {
        $this->cellRegistry = $cellRegistry;
        $this->filterRegistry = $filterRegistry;
    }

    public function loadTypes()
    {
        return [
            new Type\FilterType($this->filterRegistry),
            new Type\GridType(),
            new Type\RowType($this->cellRegistry),
        ];
    }
}
