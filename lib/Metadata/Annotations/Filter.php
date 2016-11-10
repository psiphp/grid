<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 */
class Filter
{
    public $name;
    public $type;
    public $field;
    public $options = [];
}
