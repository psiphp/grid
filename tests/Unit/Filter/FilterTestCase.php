<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Filter;

use Psi\Component\Grid\FilterDataInterface;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Comparison;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Forms;

abstract class FilterTestCase extends \PHPUnit_Framework_TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()->getFormFactory();
    }

    abstract protected function getFilter(): FilterInterface;

    protected function createForm(array $options)
    {
        $filter = $this->getFilter();

        $resolver = new OptionsResolver();
        $resolver->setDefault('capabilities', Capabilities::create([
            'supported_comparators' => [
                Comparison::EQUALS,
                Comparison::NOT_EQUALS,
                Comparison::LESS_THAN,
                Comparison::LESS_THAN_EQUAL,
                Comparison::GREATER_THAN,
                Comparison::GREATER_THAN_EQUAL,
                Comparison::IN,
                Comparison::NOT_IN,
                Comparison::CONTAINS,
                Comparison::NOT_CONTAINS,
                Comparison::NULL,
                Comparison::NOT_NULL,
            ],
        ]));
        $filter->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $filterBuilder = $this->factory->createNamedBuilder('test', FormType::class, null, [
            'data_class' => isset($options['data_class']) ? $options['data_class'] : null,
            'empty_data' => isset($options['empty_data']) ? $options['empty_data'] : null,
        ]);

        $filter->buildForm($filterBuilder, $options);

        return $filterBuilder->getForm();
    }

    public function submitFilter(array $options, array $submitData): FilterDataInterface
    {
        $form = $this->createForm($options);
        $form->submit($submitData);

        $this->assertTrue($form->isValid());
        $data = $form->getData();
        $this->assertInstanceOf(FilterDataInterface::class, $data);

        return $data;
    }

    public function getExpression(FilterDataInterface $data)
    {
        $filter = $this->getFilter();

        return $filter->getExpression('test', $data);
    }
}
