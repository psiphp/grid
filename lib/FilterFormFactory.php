<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Composite;
use Psi\Component\ObjectAgent\Query\Expression;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormFactory
{
    private $formFactory;
    private $filterRegistry;

    public function __construct(
        FormFactoryInterface $formFactory,
        FilterRegistry $filterRegistry
    ) {
        $this->formFactory = $formFactory;
        $this->filterRegistry = $filterRegistry;
    }

    public function createForm(GridMetadata $gridMetadata, Capabilities $capabilities)
    {
        $formBuilder = $this->formFactory->createBuilder(FormType::class);

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
                throw new \RuntimeException(sprintf(
                    'Data for filter named "%s" not present in form data',
                    $filterName
                ));
            }

            $field = $filterMetadata->getField() ?: $filterName;
            $filterData = $data[$filterName];

            if (null === $filterData->getValue()) {
                continue;
            }

            $filter = $this->filterRegistry->get($filterMetadata->getType());
            $expressions[] = $filter->getExpression($field, $filterData);
        }

        return new Composite(Composite::AND, $expressions);
    }
}
