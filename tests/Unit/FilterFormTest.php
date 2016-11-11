<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\FilterForm;
use Psi\Component\Grid\GridContext;
use Symfony\Component\Form\FormView;

class FilterFormTest extends \PHPUnit_Framework_TestCase
{
    private $filterForm;
    private $form;
    private $context;

    public function setUp()
    {
        $this->form = $this->prophesize(FormView::class);
        $this->context = new GridContext(\stdClass::class, []);
        $this->filterForm = new FilterForm($this->form->reveal(), $this->context);
    }

    public function testGetters()
    {
        $this->assertSame($this->form->reveal(), $this->filterForm->getForm());
    }

    public function testUrlParameters()
    {
        $urlParameters = $this->filterForm->getUrlParametersForFilter();
        $this->assertArrayNotHasKey('filter', $urlParameters);
    }
}
