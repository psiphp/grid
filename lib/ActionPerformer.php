<?php

declare(strict_types=1);

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

    /**
     * Retrieve a collection from the given agent using the given metadata and
     * identifiers and perform the named action on each member of the
     * collection.
     *
     * It should be assumed that the storage will be flushed by the action
     * which is executed.
     *
     * @throws \InvalidArgumentException If the action is not available.
     */
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
