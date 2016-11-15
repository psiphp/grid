<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Metadata\Annotations;

/**
 * @Annotation
 * @Target("ALL")
 * @Attributes({
 *     @Attribute("name", type="string", required=true),
 *     @Attribute("columns", type="array"),
 *     @Attribute("filters", type="array"),
 *     @Attribute("actions", type="array"),
 *     @Attribute("pageSize", type="int"),
 * })
 */
class Grid
{
    /** @Required */
    public $name;
    public $columns = [];
    public $filters = [];
    public $actions = [];
    public $pageSize = 50;
}
