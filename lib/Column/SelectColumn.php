<?php

namespace Psi\Component\Grid\Column;

use Psi\Component\Grid\CellInterface;
use Psi\Component\Grid\ColumnInterface;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\RowData;
use Psi\Component\Grid\View\Cell as Cell;
use Psi\Component\Grid\View\Header;
use Psi\Component\ObjectAgent\AgentFinder;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectColumn implements ColumnInterface
{
    private $agentFinder;

    public function __construct(AgentFinder $agentFinder)
    {
        $this->agentFinder = $agentFinder;
    }

    public function createCell(RowData $data, array $options): CellInterface
    {
        // TODO: This is inefficient and will not work for proxies ...
        $agent = $this->agentFinder->findFor(get_class($data->getObject()));

        return new Cell\SelectCell($agent->getIdentifier($data->getObject()));
    }

    public function createHeader(GridContext $context, array $options)
    {
        return new Header($context, $options['column_name']);
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
