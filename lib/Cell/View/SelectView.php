<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Cell\View;

use Psi\Component\Grid\CellViewInterface;

class SelectView implements CellViewInterface
{
    const INPUT_NAME = '__select__';

    private $identifier;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getInputName()
    {
        return sprintf('%s[%s]', self::INPUT_NAME, $this->identifier);
    }

    public function getValue()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariant()
    {
    }
}
