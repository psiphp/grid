<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class ColumnMetadata
{
    private $name;
    private $type;
    private $options = [];

    public function __construct(
        string $name,
        string $type,
        array $options,
        array $tags
    ) {
        $this->name = $name;
        $this->type = $type;
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
}
