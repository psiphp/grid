<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\PropertyAccess\PropertyAccess;

final class RowData implements \ArrayAccess
{
    /**
     * @var mixed
     */
    private $data;

    public function __construct($data)
    {
        if (
            false === is_object($data) &&
            false === is_array($data) &&
            false === $data instanceof \ArrayAccess
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Row data must be either an object, an array, or it must implement ArrayAccess. Got "%s"',
                gettype($data)
            ));
        }

        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function isArrayLike()
    {
        return is_array($this->data) || $this->data instanceof \ArrayAccess;
    }

    public function __get($key)
    {
        if (false === $this->isArrayLike()) {
            throw new \InvalidArgumentException(sprintf(
                'Magic __get method can only be used on array-like data.'
            ));
        }

        if (!isset($this->data[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown property "%s"', $key
            ));
        }

        return $this->data[$key];
    }

    public function offsetGet($key)
    {
        return $this->data[$key];
    }

    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }

    public function offsetSet($key, $value)
    {
        throw new \BadMethodCallException('Row data  is immutable');
    }

    public function offsetUnset($key)
    {
        throw new \BadMethodCallException('Row data  is immutable');
    }
}
