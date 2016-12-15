<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\View\Cell;
use Psi\Component\ObjectAgent\AgentFinder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectColumn implements ColumnInterface
{
    const INPUT_NAME = '_select';

    private $agentFinder;

    public function __construct(AgentFinder $agentFinder)
    {
        $this->agentFinder = $agentFinder;
    }

    public function buildCell(Cell $cell, array $options)
    {
        $cell->template = 'Select';

        // TODO: maybe use the property accessor here instead? and default to the property "id".
        $agent = $this->agentFinder->findFor(get_class($cell->context));

        $cell->value = $agent->getIdentifier($cell->context);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('property', null);
    }

    public function getParent()
    {
    }
}
