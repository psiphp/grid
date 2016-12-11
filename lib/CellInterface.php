<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

interface CellInterface
{
    /**
     * Return the *primary* value of the cell.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Return the view name - this should be used to determine
     * which template is to be rendered. If NULL is returned then
     * a default template should be assumed.
     *
     * @return string|null
     */
    public function getView();
}
