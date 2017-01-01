<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Util;

class ColumnTemplateInferrer
{
    public static function inferCellTemplate($columnClassFqn)
    {
        $shortName = substr($columnClassFqn, strrpos($columnClassFqn, '\\') + 1);
        $offset = strrpos($shortName, 'Column');

        if (false !== $offset) {
            $shortName = substr($shortName, 0, $offset);
        }

        return $shortName;
    }
}
