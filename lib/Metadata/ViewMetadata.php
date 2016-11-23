<?php

namespace Psi\Component\Grid\Metadata;

class ViewMetadata extends AbstractConfigurable
{
    public function __construct(string $type = null, array $options = [])
    {
        $type = $type ? : 'scalar';

        parent::__construct($type, $options);
    }
}
