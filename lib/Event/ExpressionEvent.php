<?php

namespace Psi\Component\Grid\Event;

use Symfony\Component\EventDispatcher\Event;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\Grid\Metadata\GridMetadata;

class ExpressionEvent extends Event
{
    private $metadata;
    private $expression;

    public function __construct(GridMetadata $metadata, Expression $expression)
    {
        $this->metadata = $metadata;
        $this->expression = $expression;
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }

    public function setExpressoin(Expression $expressoin)
    {
        $this->expressoin = $expressoin;
    }
}
