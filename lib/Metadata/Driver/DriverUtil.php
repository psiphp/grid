<?php

namespace Psi\Component\Grid\Metadata\Driver;

use Psi\Component\Grid\Metadata\SourceMetadata;
use Psi\Component\Grid\Metadata\ViewMetadata;

class DriverUtil
{
    public static function getSourceMetadata($options)
    {
        list($type, $options) = self::resolveConfigurableReference($options);

        return new SourceMetadata($type, $options);
    }

    public static function getViewMetadata($options)
    {
        list($type, $options) = self::resolveConfigurableReference($options);

        return new ViewMetadata($type, $options);
    }

    private static function resolveConfigurableReference($options)
    {
        if (is_string($options)) {
            $options = [ 'type' => $options ];
        }

        $type = isset($options['type']) ? $options['type'] : null;
        unset($options['type']);

        return [ $type, $options ];
    }
}
