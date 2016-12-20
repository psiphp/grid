<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateFilter extends AbstractComparatorFilter
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('apply', CheckboxType::class, [
            'required' => false,
        ]);
        $this->addComparatorChoice($builder, $options);
        $builder->add('value', DateType::class, []);
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
    public function isApplicable(array $filterData): bool
    {
        return (bool) $filterData['apply'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('comparators', $this->getComparatorMap());
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
