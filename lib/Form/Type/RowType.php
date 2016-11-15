<?php

namespace Psi\Component\Grid\Form\Type;

use Psi\Component\Grid\CellRegistry;
use Psi\Component\Grid\CellWithFormInterface;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\Grid\RowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RowType extends AbstractType
{
    private $registry;

    public function __construct(CellRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gridMetadata = $options['grid_metadata'];

        foreach ($gridMetadata->getColumns() as $columnName => $columnMetadata) {
            $cell = $this->registry->get($columnMetadata->getType());

            if (!$cell instanceof CellWithFormInterface) {
                continue;
            }

            $cell = $this->registry->get($columnMetadata->getType());

            $resolver = new OptionsResolver();
            $resolver->setDefault('column_name', $columnName);
            $cell->configureOptions($resolver);
            $options = $resolver->resolve($columnMetadata->getOptions());

            $cellBuilder = $builder->create($columnName, FormType::class);
            $cell->buildForm($cellBuilder, $options);
            $builder->add($cellBuilder);
        }
    }

    public function configureOptions(OptionsResolver $options)
    {
        $options->setRequired(['grid_metadata']);
        $options->setAllowedTypes('grid_metadata', GridMetadata::class);
        $options->setDefault('data_class', RowData::class);
    }
}
