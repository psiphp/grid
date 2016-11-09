<?php

namespace Psi\Component\Grid\Tests\Functional;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\CollectionsAgent;
use Psi\Bridge\ObjectAgent\Doctrine\Collections\Store;
use Psi\Component\Grid\Cell\PropertyType;
use Psi\Component\Grid\Filter\BooleanFilter;
use Psi\Component\Grid\Filter\NumberFilter;
use Psi\Component\Grid\Filter\StringFilter;
use Psi\Component\Grid\FilterFormFactory;
use Psi\Component\Grid\FilterRegistry;
use Psi\Component\Grid\GridFactory;
use Psi\Component\Grid\Metadata\Driver\AnnotationDriver;
use Psi\Component\Grid\Metadata\Driver\ArrayDriver;
use Psi\Component\ObjectAgent\AgentFinder;
use Psi\Component\View\TypeRegistry;
use Psi\Component\View\ViewFactory;
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

        $container->register('form.factory', function (Container $container) {
            $validator = Validation::createValidator();

            return Forms::createFormFactoryBuilder()
                ->addExtension(new ValidatorExtension($validator))
                ->getFormFactory();
        });

        $container->register('view.factory', function () {
            $typeRegistry = new TypeRegistry();
            $typeRegistry->register('property', new PropertyType());

            return new ViewFactory($typeRegistry);
        });

        $container->register('filter.factory', function ($container) {
            return new FilterFormFactory(
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

        $container->register('grid.factory', function ($container) {
            return new GridFactory(
                $container->get('object_agent.finder'),
                $container->get('metadata.factory'),
                $container->get('view.factory'),
                $container->get('filter.factory')
            );
        });
    }
}