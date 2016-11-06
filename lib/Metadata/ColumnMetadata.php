<?php

namespace Psi\Component\Grid\Metadata;

final class ColumnMetadata
{
    private $name;
    private $type;
    private $options = [];

    public function __construct(
        string $name,
        string $type,
        array $options
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->options = $options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
