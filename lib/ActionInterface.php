<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ActionInterface
{
    /**
     * Perform this action on the given collection using the given agent with
     * the given options.
     */
    public function perform(AgentInterface $agent, \Traversable $collection, array $options);

    /**
     * Configure the option resolver for this action.
     */
    public function configureOptions(OptionsResolver $options);
}
