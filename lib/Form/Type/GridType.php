<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Form\Type;

use Psi\Component\Grid\Metadata\GridMetadata;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rows', CollectionType::class, [
            'entry_type' => RowType::class,
            'entry_options' => [
                'grid_metadata' => $options['grid_metadata'],
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['grid_metadata']);
        $options->setAllowedTypes('grid_metadata', GridMetadata::class);
    }
}
