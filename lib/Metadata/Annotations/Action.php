<?php

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("type", type="string", required=true),
 *     @Attribute("options", type="array")
 * })
 */
class Action
{
    public $name;
    public $type;
    public $options = [];
}
