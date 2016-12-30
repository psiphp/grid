<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', ChoiceType::class, [
            'choices' => $options['choices'],
            'placeholder' => $options['placeholder'],
            'expanded' => $options['expanded'],
            'multiple' => $options['multiple'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, array $data): Expression
    {
        $comparator = is_array($data['value']) ? Comparison::IN : Comparison::EQUALS;

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
        return isset($filterData['value']) && $filterData['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('choices', []);
        $options->setDefault('placeholder', null);
        $options->setDefault('expanded', false);
        $options->setDefault('multiple', false);

        $options->setAllowedTypes('choices', ['array']);
        $options->setAllowedTypes('placeholder', ['string', 'null']);
        $options->setAllowedTypes('expanded', ['bool']);
        $options->setAllowedTypes('multiple', ['bool']);
    }
}
