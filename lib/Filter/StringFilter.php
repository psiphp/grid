<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Filter;

use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Expression;
use Psi\Component\ObjectAgent\Query\Query;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StringFilter extends AbstractComparatorFilter
{
    const TYPE_EQUAL = 'equal';
    const TYPE_EMPTY = 'empty';
    const TYPE_NOT_EMPTY = 'not_empty';
    const TYPE_CONTAINS = 'contains';
    const TYPE_NOT_CONTAINS = 'not_contains';
    const TYPE_STARTS_WITH = 'starts_with';
    const TYPE_ENDS_WITH = 'ends_with';
    const TYPE_IN = 'in';
    const TYPE_NOT_IN = 'not_in';

    private static $comparatorMap = [
        self::TYPE_EQUAL => Comparison::EQUALS,
        self::TYPE_EMPTY => Comparison::NULL,
        self::TYPE_NOT_EMPTY => Comparison::NOT_NULL,
        self::TYPE_CONTAINS => Comparison::CONTAINS,
        self::TYPE_NOT_CONTAINS => Comparison::NOT_CONTAINS,
        self::TYPE_STARTS_WITH => Comparison::CONTAINS,
        self::TYPE_ENDS_WITH => Comparison::CONTAINS,
        self::TYPE_IN => Comparison::IN,
        self::TYPE_NOT_IN => Comparison::NOT_IN,
    ];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addComparatorChoice($builder, $options);

        $builder->add('value', TextType::class, [
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $fieldName, FilterDataInterface $data): Expression
    {
        $comparator = $data->getComparator() ?: self::TYPE_EQUAL;

        return Query::comparison(
            self::$comparatorMap[$comparator],
            $fieldName,
            $this->getValue($comparator, $data->getValue())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefault('comparators', array_keys(self::$comparatorMap));
        $options->setDefault('data_class', StringFilterData::class);
        $options->setDefault('empty_data', function (FormInterface $form) {
            return new StringFilterData(
                $form->get('comparator')->getData(),
                $form->get('value')->getData()
            );
        });
    }

    protected function getComparatorMap(): array
    {
        return self::$comparatorMap;
    }

    private function getValue($comparator, $value)
    {
        switch ($comparator) {
            case self::TYPE_EQUAL:
                return $value;
            case self::TYPE_EMPTY:
                return;
            case self::TYPE_NOT_EMPTY:
                return;
            case self::TYPE_CONTAINS:
                return '%' . $value . '%';
            case self::TYPE_NOT_CONTAINS:
                return '%' . $value . '%';
            case self::TYPE_STARTS_WITH:
                return $value . '%';
            case self::TYPE_ENDS_WITH:
                return '%' . $value;
            case self::TYPE_IN:
                return array_map('trim', explode(',', $value));
            case self::TYPE_NOT_IN:
                return array_map('trim', explode(',', $value));
        }

        throw new \InvalidArgumentException(sprintf('Could not determine value for comparator "%s"', $comparator));
    }
}
