<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\Grid\Form\Type\FilterType;
use Psi\Component\Grid\Metadata\FilterMetadata;
use Psi\Component\Grid\Metadata\GridMetadata;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Composite;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FilterBarFactory
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

        $formBuilder = $this->formFactory->createNamedBuilder(self::FORM_NAME, FilterType::class, null, $options);

        return $formBuilder->getForm();
    }

    public function createExpression(GridMetadata $gridMetadata, array $data)
    {
        $expressions = [];
        foreach ($gridMetadata->getFilters() as $filterName => $filterMetadata) {
            if (!isset($data[$filterName])) {
                continue;
            }

            $this->addExpression($expressions, $filterMetadata, $filterName, $data[$filterName]);
        }

        if (empty($expressions)) {
            return;
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
}
