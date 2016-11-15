<?php

namespace Psi\Component\Grid\Tests\Unit\Action;

use Psi\Component\Grid\Action\DeleteAction;
use Psi\Component\ObjectAgent\AgentInterface;

class DeleteActionTest extends \PHPUnit_Framework_TestCase
{
    private $action;
    private $agent;

    public function setUp()
    {
        $this->action = new DeleteAction();
        $this->agent = $this->prophesize(AgentInterface::class);
    }

    public function testDelete()
    {
        $collection = new \ArrayIterator([
            $one = new \stdClass(),
             new \stdClass(),
        ]);

        $this->agent->remove($one)->shouldBeCalledTimes(2);
        $this->agent->flush()->shouldBeCalled();

        $this->action->perform($this->agent->reveal(), $collection, []);
    }
}
