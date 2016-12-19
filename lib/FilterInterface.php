<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

use Psi\Component\ObjectAgent\Query\Expression;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FilterInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);

    public function getExpression(string $fieldName, array $data): Expression;

    public function isApplicable(array $filterData): bool;

    public function configureOptions(OptionsResolver $options);
}
