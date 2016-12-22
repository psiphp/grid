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

class BooleanFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', ChoiceType::class, [
            'choices' => [
                1 => 'yes',
                0 => 'no',
            ],
            'expanded' => true,
            'placeholder' => $options['placeholder']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable(array $data): bool
    {
        return isset($data['value']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, array $data): Expression
    {
        return Query::comparison(
            Comparison::EQUALS,
            $fieldName,
            $data['value']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('placeholder', null);
    }
}
