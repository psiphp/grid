<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class ActionRegistry extends Registry
{
    public function __construct()
    {
        parent::__construct(
            ActionInterface::class,
            'action'
        );
    }
}
