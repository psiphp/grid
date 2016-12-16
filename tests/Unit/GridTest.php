<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\ActionPerformer;
use Psi\Component\Grid\Column\SelectColumn;
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
     * It should throw an exception if extra keys are in the POST request.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unexpected keys in POST: "foo", "asd", "bdsa"
     */
    public function testPerformFromPostDataNoInput()
    {
        $this->grid->performActionFromPostData([
            'foo' => 'bar',
            'asd' => 'asd',
            'bdsa' => 'bar',
        ]);
    }

    /**
     * It should throw an exception if the action key is not in the POST data.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Expected action to be in post with key "action_name",
     */
    public function testPerformFromPostNoAction()
    {
        $this->grid->performActionFromPostData([
            SelectColumn::INPUT_NAME => 'bar',
        ]);
    }

    /**
     * It should continue if no POST data for the select is present
     * (an ActionResponse should always be returned).
     */
    public function testPerformFromPostNoSelect()
    {
        $this->actionPerformer->perform(
            $this->agent->reveal(),
            $this->gridMetadata,
            'delete',
            []
        )->shouldBeCalled();

        $this->grid->performActionFromPostData([
            ActionBar::INPUT_NAME => 'delete',
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
            SelectColumn::INPUT_NAME => [
                12 => true,
                18 => true,
            ],
        ]);
    }
}
