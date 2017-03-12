<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class FilterMetadata
{
    private $name;
    private $type;
    private $options = [];
    private $field;
    private $defaults = [];
    private $tags;

    public function __construct(
        string $name,
        string $type,
        string $field = null,
        $defaults = [],
        array $options = [],
        array $tags = []
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
        $this->defaults = $defaults;
        $this->field = $field;
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

    public function getField()
    {
        return $this->field;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
