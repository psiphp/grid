<?php

namespace Psi\Component\Grid;

use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ActionInterface
{
    public function perform(AgentInterface $agent, \Traversable $collection, array $options);

    public function configureOptions(OptionsResolver $options);
}
