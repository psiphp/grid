<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\Event\ExpressionEvent;
use Psi\Component\Grid\EventDispatchingFilterBarFactory;
use Psi\Component\Grid\Events;
use Psi\Component\Grid\FilterBarFactoryInterface;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\Grid\View\FilterBar;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatchingFilterBarFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $dispatcher;
    private $innerFactory;
    private $factory;
    private $filterBar;
    private $metadata;
    private $capabilities;

    public function setUp()
    {
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);
        $this->innerFactory = $this->prophesize(FilterBarFactoryInterface::class);

        $this->factory = new EventDispatchingFilterBarFactory(
            $this->innerFactory->reveal(),
            $this->dispatcher->reveal()
        );

        $this->filterBar = $this->prophesize(FilterBar::class);
        $this->metadata = MetadataUtil::createGrid('main', []);
        $this->capabilities = Capabilities::create([]);
        $this->expression = Query::comparison('eq', 'foo', 'bar');
    }

    /**
     * It should dispatch events when creating an expression.
     */
    public function testDispatchEvents()
    {
        $this->innerFactory->createExpression($this->metadata, [])->willReturn($this->expression);
        $this->dispatcher->dispatch(Events::EXPRESSION_CREATED, new ExpressionEvent($this->metadata, $this->expression))->shouldBeCalled();
        $this->factory->createExpression($this->metadata, []);
    }
}
