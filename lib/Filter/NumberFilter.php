<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberFilter implements FilterInterface
{
    private static $validComparators = [
        Comparison::EQUALS,
        Comparison::NOT_EQUALS,
        Comparison::GREATER_THAN,
        Comparison::GREATER_THAN_EQUAL,
        Comparison::LESS_THAN_EQUAL,
        Comparison::LESS_THAN,
    ];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comparator', ChoiceType::class, [
            'choices' => $this->getChoices(
                $options['capabilities']->getSupportedComparators(),
                $options['comparators']
            ),
        ]);
        $builder->add('value', NumberType::class, [
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, FilterDataInterface $data): Expression
    {
        $comparator = $data->getComparator() ?: Comparison::EQUALS;

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
        $options->setDefault('comparators', self::$validComparators);
        $options->setDefault('data_class', NumberFilterData::class);
        $options->setDefault('empty_data', function (FormInterface $form) {
            return new NumberFilterData(
                $form->get('comparator')->getData(),
                $form->get('value')->getData()
            );
        });
    }

    private function getChoices(array $supportedComparators, array $enabledComparators)
    {
        $supported = array_filter(self::$validComparators, function ($comparator) use ($supportedComparators) {
            return in_array($comparator, $supportedComparators);
        });

        $supported = array_filter($supported, function ($comparator) use ($enabledComparators) {
            return in_array($comparator, $enabledComparators);
        });

        return array_combine($supported, $supported);
    }
}
