<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("type", type="string", required=true),
 *     @Attribute("field", type="string"),
 *     @Attribute("options", type="array")
 * })
 */
class Filter
{
    public $name;
    public $type;
    public $field;
    public $options = [];
}
