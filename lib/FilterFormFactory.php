<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Composite;
use Psi\Component\ObjectAgent\Query\Expression;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormFactory
{
    const FORM_NAME = 'filter';

    private $formFactory;
    private $filterRegistry;

    public function __construct(
        FormFactoryInterface $formFactory,
        FilterRegistry $filterRegistry
    ) {
        $this->formFactory = $formFactory;
        $this->filterRegistry = $filterRegistry;
    }

    public function createForm(GridMetadata $gridMetadata, Capabilities $capabilities): FormInterface
    {
        $formBuilder = $this->formFactory->createNamedBuilder(self::FORM_NAME, FormType::class);

        foreach ($gridMetadata->getFilters() as $filterName => $filterMetadata) {
            $filter = $this->filterRegistry->get($filterMetadata->getType());

            $resolver = new OptionsResolver();
            $resolver->setDefault('capabilities', $capabilities);
            $filter->configureOptions($resolver);

            $options = $resolver->resolve($filterMetadata->getOptions());
            $filterBuilder = $formBuilder->create($filterName, FormType::class, [
                'data_class' => isset($options['data_class']) ? $options['data_class'] : null,
                'empty_data' => isset($options['empty_data']) ? $options['empty_data'] : null,
            ]);

            $filter->buildForm($filterBuilder, $options);
            $formBuilder->add($filterBuilder);
        }

        return $formBuilder->getForm();
    }

    public function createExpression(GridMetadata $gridMetadata, array $data): Expression
    {
        $expressions = [];
        foreach ($gridMetadata->getFilters() as $filterName => $filterMetadata) {
            if (!isset($data[$filterName])) {
                continue;
            }

            $this->addExpression($expressions, $filterMetadata, $filterName, $data[$filterName]);
        }

        return new Composite(Composite::AND, $expressions);
    }

    private function addExpression(
        &$expressions,
        FilterMetadata $filterMetadata,
        $filterName,
        FilterDataInterface $filterData
    ) {
        $field = $filterMetadata->getField() ?: $filterName;

        if (null === $filterData->getValue()) {
            return;
        }

        $filter = $this->filterRegistry->get($filterMetadata->getType());
        $expressions[] = $filter->getExpression($field, $filterData);
    }
}
