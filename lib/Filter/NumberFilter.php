<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFilter extends AbstractComparatorFilter
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addComparatorChoice($builder, $options);

        $builder->add('value', NumberType::class, [
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, array $data): Expression
    {
        $comparator = $data['comparator'] ?: Comparison::EQUALS;

        return Query::comparison(
            $comparator,
            $fieldName,
            $data['value']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('comparators', $this->getComparatorMap());
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(array $filterData): bool
    {
        return isset($filterData['value']);
    }

    protected function getComparatorMap(): array
    {
        $supported = [
            Comparison::EQUALS,
            Comparison::NOT_EQUALS,
            Comparison::GREATER_THAN,
            Comparison::GREATER_THAN_EQUAL,
            Comparison::LESS_THAN_EQUAL,
            Comparison::LESS_THAN,
        ];

        return array_combine($supported, $supported);
    }
}
