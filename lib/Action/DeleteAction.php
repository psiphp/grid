<?php

namespace Psi\Component\Grid\Action;

use Psi\Component\Grid\ActionInterface;
use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteAction implements ActionInterface
{
    public function perform(AgentInterface $agent, \Traversable $collection, array $options)
    {
        foreach ($collection as $object) {
            $agent->remove($object);
        }

        $agent->flush();
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
