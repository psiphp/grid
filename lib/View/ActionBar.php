<?php

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\Metadata\GridMetadata;

class ActionBar
{
    const INPUT_NAME = '__action_name__';

    private $gridMetadata;

    public function __construct(
        GridMetadata $gridMetadata
    ) {
        $this->gridMetadata = $gridMetadata;
    }

    public function getAvailableActionNames()
    {
        $names = array_keys($this->gridMetadata->getActions());

        return array_combine($names, $names);
    }

    public function getInputName()
    {
        return self::INPUT_NAME;
    }
}
