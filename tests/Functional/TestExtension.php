<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\CollectionsAgent;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\Store;
use Psi\Component\Grid\Action\DeleteAction;
use Psi\Component\Grid\ActionPerformer;
use Psi\Component\Grid\ActionRegistry;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\Column\SelectColumn;
use Psi\Component\Grid\ColumnFactory;
use Psi\Component\Grid\ColumnRegistry;
use Psi\Component\Grid\Filter\BooleanFilter;
use Psi\Component\Grid\Filter\NumberFilter;
use Psi\Component\Grid\Filter\StringFilter;
use Psi\Component\Grid\FilterBarFactory;
use Psi\Component\Grid\FilterRegistry;
use Psi\Component\Grid\Form\GridExtension;
use Psi\Component\Grid\GridFactory;
use Psi\Component\Grid\GridViewFactory;
use Psi\Component\Grid\Metadata\Driver\AnnotationDriver;
use Psi\Component\Grid\Metadata\Driver\ArrayDriver;
use Psi\Component\Grid\QueryFactory;
use Psi\Component\ObjectAgent\AgentFinder;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validation;

class TestExtension implements ExtensionInterface
{
    public function getDefaultConfig()
    {
        return [
            'collections' => [],
            'mapping' => [
            ],
        ];
    }

    public function load(Container $container)
    {
        $container->register('annotation_reader', function () {
            return new AnnotationReader();
        });

        $container->register('metadata.array_driver', function (Container $container) {
            return new ArrayDriver($container->getParameter('mapping'));
        });
        $container->register('metadata.annotation_driver', function (Container $container) {
            return new AnnotationDriver($container->get('annotation_reader'));
        });
        $container->register('metadata.chain_driver', function (Container $container) {
            return new DriverChain([
                $container->get('metadata.array_driver'),
                $container->get('metadata.annotation_driver'),
            ]);
        });

        $container->register('metadata.factory', function (Container $container) {
            return new MetadataFactory($container->get('metadata.chain_driver'));
        });

        $container->register('object_agent.finder', function (Container $container) {
            return new AgentFinder([
                'collection' => new CollectionsAgent(new Store(
                    $container->getParameter('collections')
                )),
            ]);
        });

        $container->register('query.factory', function (Container $container) {
            return new QueryFactory($container->get('metadata.factory'));
        });

        $container->register('form.factory', function (Container $container) {
            $validator = Validation::createValidator();

            return Forms::createFormFactoryBuilder()
                ->addExtension(new ValidatorExtension($validator))
                ->addExtension(new GridExtension(
                    $container->get('column.registry'),
                    $container->get('filter.registry')
                ))
                ->getFormFactory();
        });

        $container->register('column.registry', function ($container) {
            $cellRegistry = new ColumnRegistry();
            $cellRegistry->register('property', new PropertyColumn());
            $cellRegistry->register('select', new SelectColumn($container->get('object_agent.finder')));

            return $cellRegistry;
        });

        $container->register('cell.factory', function ($container) {
            return new ColumnFactory($container->get('column.registry'));
        });

        $container->register('filter.factory', function ($container) {
            return new FilterBarFactory(
                $container->get('form.factory'),
                $container->get('filter.registry')
            );
        });

        $container->register('filter.registry', function ($container) {
            $registry = new FilterRegistry();
            $registry->register('string', new StringFilter());
            $registry->register('number', new NumberFilter());
            $registry->register('boolean', new BooleanFilter());

            return $registry;
        });

        $container->register('grid.view.factory', function ($container) {
            return new GridViewFactory(
                $container->get('cell.factory'),
                $container->get('filter.factory'),
                $container->get('query.factory')
            );
        });
        $container->register('action.registry', function ($container) {
            $deleteAction = new DeleteAction();
            $registry = new ActionRegistry();
            $registry->register('delete', $deleteAction);

            return $registry;
        });
        $container->register('action.performer', function ($container) {
            return new ActionPerformer(
                $container->get('action.registry')
            );
        });
        $container->register('grid.factory', function ($container) {
            return new GridFactory(
                $container->get('object_agent.finder'),
                $container->get('metadata.factory'),
                $container->get('grid.view.factory'),
                $container->get('action.performer')
            );
        });
    }
}
