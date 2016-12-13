<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class ColumnRegistry extends Registry
{
    public function __construct()
    {
        parent::__construct(
            ColumnInterface::class,
            'cell'
        );
    }
}
