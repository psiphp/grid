<?php

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionPerformer
{
    private $registry;

    public function __construct(ActionRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function perform(
        AgentInterface $agent,
        GridMetadata $gridMetadata,
        string $actionName,
        array $selectedIdentifiers
    ) {
        $actionMetadatas = $gridMetadata->getActions();
        if (!isset($actionMetadatas[$actionName])) {
            throw new \InvalidArgumentException(sprintf(
                'Action "%s" is not available for class "%s". Availabile actions: "%s"',
                $actionName, $gridMetadata->getClassMetadata()->name, implode('", "', array_keys($actionMetadatas))
            ));
        }

        $collection = $agent->findMany($selectedIdentifiers, $gridMetadata->getClassMetadata()->name);

        $actionMetadata = $actionMetadatas[$actionName];
        $action = $this->registry->get($actionMetadata->getType());
        $options = new OptionsResolver();
        $action->configureOptions($options);
        $options = $options->resolve($actionMetadata->getOptions());

        $action->perform($agent, $collection, $options);
    }
}
