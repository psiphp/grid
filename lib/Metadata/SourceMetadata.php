<?php

namespace Psi\Component\Grid\Metadata;

class SourceMetadata extends AbstractConfigurable
{
    public function __construct(string $type = null, array $options = [])
    {
        $type = $type ? : 'property';

        parent::__construct($type, $options);
    }
}
