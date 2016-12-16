<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Column\SelectColumn;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\View\ActionBar;
use Psi\Component\ObjectAgent\AgentInterface;

class Grid
{
    private $agent;
    private $gridContext;
    private $gridMetadata;
    private $gridViewFactory;
    private $actionPerformer;

    public function __construct(
        GridViewFactory $gridViewFactory,
        ActionPerformer $actionPerformer,
        AgentInterface $agent,
        GridContext $gridContext,
        GridMetadata $gridMetadata
    ) {
        $this->agent = $agent;
        $this->gridContext = $gridContext;
        $this->gridMetadata = $gridMetadata;
        $this->gridViewFactory = $gridViewFactory;
        $this->actionPerformer = $actionPerformer;
    }

    public function createView()
    {
        return $this->gridViewFactory->createView(
            $this->agent,
            $this->gridContext,
            $this->gridMetadata
        );
    }

    public function performActionFromPostData(array $postData): ActionResponseInterface
    {
        $valid = [
            ActionBar::INPUT_NAME,
            SelectColumn::INPUT_NAME,
        ];

        if ($diff = array_diff(array_keys($postData), $valid)) {
            throw new \InvalidArgumentException(sprintf(
                'Unexpected keys in POST: "%s", valid keys: "%s"',
                implode('", "', $diff), implode('", "', $valid)
            ));
        }

        if (!isset($postData[ActionBar::INPUT_NAME])) {
            throw new \InvalidArgumentException(sprintf(
                'Expected action to be in post with key "%s", but it was not there.',
                ActionBar::INPUT_NAME
            ));
        }

        // allow empty submissions
        if (!isset($postData[SelectColumn::INPUT_NAME])) {
            $postData[SelectColumn::INPUT_NAME] = [];
        }

        $actionName = $postData[ActionBar::INPUT_NAME];
        $selectData = $postData[SelectColumn::INPUT_NAME];

        if (!is_array($selectData)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected data from select column to be an array, but got "%s"',
                gettype($selectData)
            ));
        }

        $selectedIdentifiers = array_keys($selectData);

        return $this->performAction($actionName, $selectedIdentifiers);
    }

    public function performAction(string $actionName, array $selectedIdentifiers): ActionResponseInterface
    {
        return $this->actionPerformer->perform(
            $this->agent,
            $this->gridMetadata,
            $actionName,
            $selectedIdentifiers
        );
    }
}
