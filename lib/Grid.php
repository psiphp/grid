<?php

namespace Psi\Component\Grid;

use Psi\Component\Grid\Cell\View\SelectView;
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

    public function performActionFromPostData(array $postData)
    {
        $required = [
            ActionBar::INPUT_NAME,
            SelectView::INPUT_NAME,
        ];

        if (array_diff($required, array_keys($postData))) {
            throw new \InvalidArgumentException(sprintf(
                'Expected all keys "%s" in post data, but (only) got "%s"',
                implode('", "', $required), implode('", "', array_keys($postData))
            ));
        }

        $actionName = $postData[ActionBar::INPUT_NAME];
        $selectedIdentifiers = array_keys($postData[SelectView::INPUT_NAME]);

        $this->performAction($actionName, $selectedIdentifiers);
    }

    public function performAction(string $actionName, array $selectedIdentifiers)
    {
        $this->actionPerformer->perform(
            $this->agent,
            $this->gridMetadata,
            $actionName,
            $selectedIdentifiers
        );
    }
}
