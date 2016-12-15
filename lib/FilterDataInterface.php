<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

interface FilterDataInterface
{
    public function getValue();

    public function isApplicable(): bool;
}
