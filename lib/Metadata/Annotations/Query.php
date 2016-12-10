<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Target("ALL")
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("selects", type="array"),
 *     @Attribute("criteria", type="array"),
 *     @Attribute("joins", type="array"),
 * })
 */
class Query
{
    /** @Required */
    public $name;
    public $selects = [];
    public $criteria = [];
    public $joins = [];
}
