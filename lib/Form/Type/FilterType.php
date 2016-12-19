<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Form\Type;

use Psi\Component\Grid\FilterRegistry;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    private $registry;

    public function __construct(FilterRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gridMetadata = $options['grid_metadata'];

        foreach ($gridMetadata->getFilters() as $filterName => $filterMetadata) {
            $filter = $this->registry->get($filterMetadata->getType());

            $resolver = new OptionsResolver();
            $resolver->setDefault('capabilities', $options['capabilities']);
            $filter->configureOptions($resolver);

            $options = $resolver->resolve($filterMetadata->getOptions());
            $filterBuilder = $builder->create($filterName, FormType::class, []);

            $filter->buildForm($filterBuilder, $options);
            $builder->add($filterBuilder);
        }
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('csrf_protection', false);
        $options->setRequired(['grid_metadata']);
        $options->setAllowedTypes('grid_metadata', GridMetadata::class);
        $options->setRequired(['capabilities']);
        $options->setAllowedTypes('capabilities', Capabilities::class);
    }
}
