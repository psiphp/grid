<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Form\Type\FilterType;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Composite;
use Psi\Component\ObjectAgent\Query\Expression;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FilterBarFactory implements FilterBarFactoryInterface
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
        $options = [
            'grid_metadata' => $gridMetadata,
            'capabilities' => $capabilities,
        ];

        $defaults = $this->getFilterDefaults($gridMetadata);
        $formBuilder = $this->formFactory->createNamedBuilder(self::FORM_NAME, FilterType::class, $defaults, $options);

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
        array $filterData
    ) {
        $field = $filterMetadata->getField() ?: $filterName;

        $filter = $this->filterRegistry->get($filterMetadata->getType());

        if (false === $filter->isApplicable($filterData)) {
            return;
        }

        $expressions[] = $filter->getExpression($field, $filterData);
    }

    private function getFilterDefaults(GridMetadata  $gridMetadata)
    {
        $defaults = [];
        foreach ($gridMetadata->getFilters() as $filterName => $filterMetadata) {
            $defaults[$filterName] = $filterMetadata->getDefaults();
        }

        return $defaults;
    }
}
