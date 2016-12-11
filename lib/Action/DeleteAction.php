<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Action;

use Psi\Component\Grid\ActionInterface;
use Psi\Component\ObjectAgent\AgentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psi\Component\Grid\ActionResponseInterface;
use Psi\Component\Grid\ActionResponse;

class DeleteAction implements ActionInterface
{
    public function perform(AgentInterface $agent, string $classFqn, array $identifiers, array $options): ActionResponseInterface
    {
        $collection = $agent->findMany($identifiers, $classFqn);

        $errors = [];
        $affected = count($collection);

        try {
            foreach ($collection as $object) {
                $agent->remove($object);
            }
            $agent->flush();
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            $affected = 0;
        }

        return ActionResponse::create([
            'errors' => $errors,
            'affected' => $affected,
        ]);
    }

    public function configureOptions(OptionsResolver $options)
    {
    }
}
