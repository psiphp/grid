<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

/**
 * TODO: Tests for this class.
 */
final class RowData
{
    private $object;

    private function __construct()
    {
    }

    public static function fromObject($object): RowData
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf(
                'Object must be an object, got: "%s"',
                gettype($object)
            ));
        }

        $instance = new self();
        $instance->object = $object;

        return $instance;
    }

    public function getObject()
    {
        return $this->object;
    }
}
