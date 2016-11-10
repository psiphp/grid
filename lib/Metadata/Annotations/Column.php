<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 */
class Column
{
    public $name;
    public $type;
    public $options = [];
}
