<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * TODO: Tests for this class.
 */
final class RowData implements \ArrayAccess
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    private function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->accessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public static function fromObject($data): RowData
    {
        $instance = new self();
        $instance->data = $data;

        return $instance;
    }

    public function getData()
    {
        return $this->data;
    }

    public function isArrayLike()
    {
        return is_array($this->data) || $this->data instanceof \ArrayAccess && isset($this->data[$key]);
    }

    public function __get($key)
    {
        if (is_array($this->data) || $this->data instanceof \ArrayAccess && isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new \InvalidArgumentException(sprintf(
            'Magic __get method can only be used on array-like data.'
        ));
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
