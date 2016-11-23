<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("source", type="mixed", required=true),
 *     @Attribute("view", type="mixed", required=true),
 *     @Attribute("options", type="array")
 * })
 */
class Column
{
    public $name;
    public $source;
    public $view;
    public $options = [];
}
