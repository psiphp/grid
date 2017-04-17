<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

use Psi\Component\Grid\Grid;

final class ColumnMetadata
{
    private $name;
    private $type;
    private $groups = [];
    private $options = [];

    public function __construct(
        string $name,
        string $type,
        array $groups,
        array $options,
        array $tags
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->groups = $groups ?: [ Grid::DEFAULT_GROUP ];
        $this->options = $options;
        $this->tags = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}
