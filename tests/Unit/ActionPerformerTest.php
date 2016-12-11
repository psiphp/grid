<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Prophecy\Argument;
use Psi\Component\Grid\ActionInterface;
use Psi\Component\Grid\ActionPerformer;
use Psi\Component\Grid\ActionRegistry;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionPerformerTest extends \PHPUnit_Framework_TestCase
{
    private $registry;
    private $agent;
    private $gridMetadata;
    private $action;

    public function setUp()
    {
        $this->registry = $this->prophesize(ActionRegistry::class);

        $this->agent = $this->prophesize(AgentInterface::class);
        $this->gridMetadata = MetadataUtil::createGrid('foo', [
            'actions' => [
                'action_one' => [
                    'type' => 'delete',
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ]);
        MetadataUtil::createClass(\stdClass::class, [$this->gridMetadata]);

        $this->performer = new ActionPerformer(
            $this->registry->reveal()
        );
        $this->action = $this->prophesize(ActionInterface::class);
    }

    /**
     * It should load the collection and perform the named action.
     */
    public function testLoadAndPerform()
    {
        $identifiers = [123, 456];
        $collection = new \ArrayIterator([
        ]);

        $this->registry->get('delete')->willReturn($this->action->reveal());
        $this->agent->findMany($identifiers, \stdClass::class)->willReturn($collection);
        $this->action->configureOptions(Argument::type(OptionsResolver::class))->will(function ($args) {
            $args[0]->setDefault('foo', 'zzz');
        });
        $this->action->perform($this->agent->reveal(), $collection, ['foo' => 'bar'])->shouldBeCalled();

        $this->performer->perform(
            $this->agent->reveal(),
            $this->gridMetadata,
            'action_one',
            $identifiers
        );
    }

    /**
     * It should throw an exception if the action is not available.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Action "barbar" is not available for class
     */
    public function testPerformInvalidAction()
    {
        $this->performer->perform(
            $this->agent->reveal(),
            $this->gridMetadata,
            'barbar',
            []
        );
    }
}
