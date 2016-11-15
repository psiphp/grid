<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata;

final class ActionMetadata
{
    private $name;
    private $type;
    private $options;

    public function __construct(
        string $name,
        string $type,
        array  $options
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
