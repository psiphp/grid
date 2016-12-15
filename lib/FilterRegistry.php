<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class FilterRegistry extends Registry
{
    public function __construct()
    {
        parent::__construct(
            FilterInterface::class,
            'filter'
        );
    }
}
