<?php

declare(strict_types=1);

namespace Psi\Component\Grid\View;

use Psi\Component\Grid\Metadata\GridMetadata;

class ActionBar
{
    const INPUT_NAME = 'action_name';

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
