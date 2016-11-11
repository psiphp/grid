<?php

namespace Psi\Component\Grid\Tests\Functional;

use Psi\Component\Grid\Filter\StringFilterData;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\ObjectAgent\Capabilities;
use Psi\Component\ObjectAgent\Query\Comparison;
use Psi\Component\ObjectAgent\Query\Composite;

class FilterFormFactoryTest extends GridTestCase
{
    /**
     * It should create a form.
     */
    public function testCreateForm()
    {
        $gridMetadata = MetadataUtil::createGrid('foobar', [
            'filters' => [
                'title' => [
                    'type' => 'string',
                    'options' => [
                        'comparators' => ['equal'],
                    ],
                ],
                'foobar' => [
                    'type' => 'string',
                    'options' => [
                        'comparators' => ['equal'],
                    ],
                ],
            ],
        ]);
        $form = $this->create()->createForm($gridMetadata, Capabilities::create([]));
        $this->assertEquals('filter', $form->getName());
        $this->assertCount(2, $form->all());
    }

    /**
     * It should create expressions.
     */
    public function testCreateExpression()
    {
        $gridMetadata = MetadataUtil::createGrid('foobar', [
            'filters' => [
                'title' => [
                    'type' => 'string',
                    'options' => [
                        'comparators' => ['equal'],
                    ],
                ],
                'foobar' => [
                    'type' => 'string',
                    'options' => [
                        'comparators' => ['equal'],
                    ],
                ],
            ],
        ]);

        $expression = $this->create()->createExpression($gridMetadata, [
            'title' => new StringFilterData('equal', 'foobar'),
        ]);
        $this->assertInstanceOf(Composite::class, $expression);
        $this->assertCount(1, $expression->getExpressions());
        $this->assertInstanceOf(Comparison::class, $expression->getExpressions()[0]);
        $comparison = $expression->getExpressions()[0];
        $this->assertEquals('eq', $comparison->getComparator());
        $this->assertEquals('foobar', $comparison->getValue());
    }

    private function create(array $gridMapping = [])
    {
        $container = $this->createContainer([]);

        return $container->get('filter.factory');
    }
}