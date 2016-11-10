<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class FilterMetadata
{
    private $name;
    private $type;
    private $options = [];
    private $field;

    public function __construct(
        string $name,
        string $type,
        string $field = null,
        array $options = []
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
        $this->field = $field;
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
}
