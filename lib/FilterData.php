<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

class FilterData extends \ArrayObject
{
    public function offsetGet($key)
    {
        if (!isset($this[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown key "%s", known keys "%s"',
                $key, implode('", "', array_keys((array) $this))
            ));
        }

        return parent::offsetGet($key);
    }

    public function __get($key)
    {
        return  $this[$key];
    }
}
