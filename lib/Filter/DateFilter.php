<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateFilter extends AbstractComparatorFilter
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addComparatorChoice($builder, $options);

        $builder->add('apply', CheckboxType::class, []);
        $builder->add('value', DateType::class, []);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, FilterDataInterface $data): Expression
    {
        $comparator = $data->getComparator() ?: self::TYPE_EQUAL;

        return Query::comparison(
            $comparator,
            $fieldName,
            $data->getValue()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('comparators', $this->getComparatorMap());
        $options->setDefault('data_class', DateFilterData::class);
        $options->setDefault('empty_data', function (FormInterface $form) {
            return new DateFilterData(
                $form->get('apply')->getData(),
                $form->get('comparator')->getData(),
                $form->get('value')->getData()
            );
        });
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
