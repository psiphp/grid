<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\ActionPerformer;
use Psi\Component\Grid\Cell\View\SelectView;
use Psi\Component\Grid\Grid;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\GridViewFactory;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\ActionBar;
use Psi\Component\ObjectAgent\AgentInterface;

class GridTest extends \PHPUnit_Framework_TestCase
{
    private $grid;
    private $gridContext;
    private $gridMetadata;
    private $gridViewFactory;
    private $actionPerformer;

    public function setUp()
    {
        $this->agent = $this->prophesize(AgentInterface::class);
        $this->gridContext = new GridContext(\stdClass::class, []);
        $this->gridMetadata = MetadataUtil::createGrid('test', []);
        $this->gridViewFactory = $this->prophesize(GridViewFactory::class);
        $this->actionPerformer = $this->prophesize(ActionPerformer::class);

        $this->grid = new Grid(
            $this->gridViewFactory->reveal(),
            $this->actionPerformer->reveal(),
            $this->agent->reveal(),
            $this->gridContext,
            $this->gridMetadata
        );
    }

    /**
     * It should throw an exception if the required input is not available in the post data.
     */
    public function testPerformFromPostDataNoInput()
    {
        $this->setExpectedException(\InvalidArgumentException::class, SelectView::INPUT_NAME);
        $this->grid->performActionFromPostData([
            ActionBar::INPUT_NAME => 'arg',
        ]);
    }

    /**
     * It should perform an action from the post data.
     */
    public function testPerformFromPost()
    {
        $this->actionPerformer->perform(
            $this->agent->reveal(),
            $this->gridMetadata,
            'delete',
            [12, 18]
        )->shouldBeCalled();

        $this->grid->performActionFromPostData([
            ActionBar::INPUT_NAME => 'delete',
            SelectView::INPUT_NAME => [
                12 => true,
                18 => true,
            ],
        ]);
    }
}
