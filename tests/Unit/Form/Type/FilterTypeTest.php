<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Form\Type;

use Prophecy\Argument;
use Psi\Component\Grid\FilterInterface;
use Psi\Component\Grid\FilterRegistry;
use Psi\Component\Grid\Form\Type\FilterType;
use Psi\Component\Grid\Tests\Util\MetadataUtil;
use Psi\Component\ObjectAgent\Capabilities;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterTypeTest extends TypeTestCase
{
    private $registry;
    private $type;
    private $filtrer;

    public function setUp()
    {
        $this->registry = $this->prophesize(FilterRegistry::class);
        $this->filter = $this->prophesize(FilterInterface::class);
        parent::setUp();
    }

    /**
     * It should build the form.
     */
    public function testBuildForm()
    {
        $capabilities = Capabilities::create([]);
        $gridMetadata = MetadataUtil::createGrid('foo', [
            'filters' => [
                'one' => [
                    'type' => 'foobar',
                    'options' => [
                        'label' => 'foobar',
                    ],
                ],
            ],
        ]);
        $this->registry->get('foobar')->willReturn($this->filter->reveal());

        $this->filter->configureOptions(Argument::type(OptionsResolver::class))->shouldBeCalled();
        $this->filter->buildForm(Argument::type(FormBuilderInterface::class), Argument::type('array'))->shouldBeCalled();

        $form = $this->factory->create(FilterType::class, null, [
            'grid_metadata' => $gridMetadata,
            'capabilities' => $capabilities,
        ]);

        $this->assertCount(1, $form->all());
    }

    protected function getExtensions()
    {
        $type = new FilterType($this->registry->reveal());

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
