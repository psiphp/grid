<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Metadata\MetadataFactory;

class GridMetadataFactory
{
    private $metadataFactory;

    public function __construct(MetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function getGridMetadata(string $classFqn, string $variant)
    {
        if (null === $metadata = $this->metadataFactory->getMetadataForClass($classFqn)) {
            throw new \InvalidArgumentException('Could not locate grid metadata');
        }

        return $this->resolveGridMetadata($metadata->getGrids(), $variant);
    }

    private function resolveGridMetadata(array $grids, string $variant = null)
    {
        if (empty($grids)) {
            throw new \InvalidArgumentException('No grid variants are available');
        }

        // if no explicit grid variant is requested, return the first one that
        // was defined.
        if (null === $variant) {
            return reset($grids);
        }

        if (!isset($grids[$variant])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown grid variant "%s", available variants: "%s"',
                $variant, implode('", "', array_keys($grids))
            ));
        }

        return $grids[$variant];
    }
}
