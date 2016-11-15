<?php

namespace Psi\Component\Grid\Cell;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\CellViewInterface;
use Psi\Component\Grid\RowData;
use Psi\Component\ObjectAgent\AgentFinder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectCell implements CellInterface
{
    private $agentFinder;

    public function __construct(AgentFinder $agentFinder)
    {
        $this->agentFinder = $agentFinder;
    }

    public function createView(RowData $data, array $options): CellViewInterface
    {
        // TODO: This is inefficient and will not work for proxies ...
        $agent = $this->agentFinder->findFor(get_class($data->getObject()));

        return new View\SelectView($agent->getIdentifier($data->getObject()));
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
