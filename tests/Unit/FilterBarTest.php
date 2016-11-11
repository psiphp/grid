<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\FilterBar;
use Psi\Component\Grid\GridContext;
use Symfony\Component\Form\FormView;

class FilterBarTest extends \PHPUnit_Framework_TestCase
{
    private $filterBar;
    private $form;
    private $context;

    public function setUp()
    {
        $this->form = $this->prophesize(FormView::class);
        $this->context = new GridContext(\stdClass::class, []);
        $this->filterBar = new FilterBar($this->form->reveal(), $this->context);
    }

    public function testGetters()
    {
        $this->assertSame($this->form->reveal(), $this->filterBar->getForm());
    }

    public function testUrlParameters()
    {
        $urlParameters = $this->filterBar->getUrlParametersForFilter();
        $this->assertArrayNotHasKey('filter', $urlParameters);
    }
}
