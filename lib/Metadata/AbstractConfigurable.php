<?php

namespace Psi\Component\Grid\Metadata;

abstract class AbstractConfigurable
{
    private $type;
    private $options = [];

    public function __construct(string $type = null, array $options = [])
    {
        $this->type = $type ? : 'property';
        $this->options = $options;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
    
    
}
