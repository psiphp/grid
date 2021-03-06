<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Event\ExpressionEvent;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Expression;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

class EventDispatchingFilterBarFactory implements FilterBarFactoryInterface
{
    const FORM_NAME = 'filter';

    private $innerFactory;
    private $dispatcher;

    public function __construct(
        FilterBarFactoryInterface $factory,
        EventDispatcherInterface $dispatcher
    ) {
        $this->innerFactory = $factory;
        $this->dispatcher = $dispatcher;
    }

    public function createForm(GridMetadata $gridMetadata, Capabilities $capabilities): FormInterface
    {
        return $this->innerFactory->createForm($gridMetadata, $capabilities);
    }

    public function createExpression(GridMetadata $gridMetadata, array $data): Expression
    {
        $expression = $this->innerFactory->createExpression($gridMetadata, $data);

        $event = new ExpressionEvent($gridMetadata, $expression);
        $this->dispatcher->dispatch(Events::EXPRESSION_CREATED, $event);

        return $event->getExpression();
    }
}
