<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("type", type="string", required=true),
 *     @Attribute("options", type="array"),
 *     @Attribute("groups", type="array"),
 *     @Attribute("tags", type="array")
 * })
 */
class Column
{
    public $name;
    public $type;
    public $groups = [];
    public $options = [];
    public $tags = [];
}
