<?php

namespace Psi\Component\Grid\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\Grid\ActionPerformer;
use Psi\Component\Grid\Grid;
use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\GridViewFactory;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\ActionBar;
use Psi\Component\Grid\View\Cell\SelectCell;
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
     * @expectedExceptionMessage Unexpected keys in POST: "foo", valid keys: "__action_name__", "__select__"
     */
    public function testPerformFromPostDataNoInput()
    {
        $this->grid->performActionFromPostData([
            'foo' => 'bar',
            ActionBar::INPUT_NAME => 'asd',
            SelectCell::INPUT_NAME => 'bar',
        ]);
    }

    /**
     * It should throw an exception if the action key is not in the POST data.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Expected action to be in post with key "__action_name__",
     */
    public function testPerformFromPostNoAction()
    {
        $this->grid->performActionFromPostData([
            SelectCell::INPUT_NAME => 'bar',
        ]);
    }

    /**
     * It should return early if no select data is present.
     */
    public function testPerformFromPostNoSelect()
    {
        $this->actionPerformer->perform(Argument::cetera())->shouldNotBeCalled();

        $this->grid->performActionFromPostData([
            ActionBar::INPUT_NAME => 'asd',
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
            SelectCell::INPUT_NAME => [
                12 => true,
                18 => true,
            ],
        ]);
    }
}
