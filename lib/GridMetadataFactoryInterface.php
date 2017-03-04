<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;
use Psi\Component\Grid\Metadata\GridMetadata;

interface GridMetadataFactoryInterface
{
    public function getGridMetadata(string $classFqn, string $variant): GridMetadata;
}
