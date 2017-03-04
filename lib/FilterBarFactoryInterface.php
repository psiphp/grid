<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Form\Type\FilterType;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Composite;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Psi\Component\ObjectAgent\Query\Expression;

interface FilterBarFactoryInterface
{
    public function createForm(GridMetadata $gridMetadata, Capabilities $capabilities): FormInterface;

    /**
     * @return Expression|null
     */
    public function createExpression(GridMetadata $gridMetadata, array $data);
}
