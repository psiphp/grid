<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Sylius\Component\Registry\ServiceRegistry;

class CellRegistry extends ServiceRegistry
{
    public function __construct()
    {
        parent::__construct(
            CellInterface::class,
            'cell'
        );
    }
}
