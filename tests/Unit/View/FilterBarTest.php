<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\View;

use Psi\Component\Grid\GridContext;
use Psi\Component\Grid\View\FilterBar;
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

    public function testUrlParametersForReset()
    {
        $urlParameters = $this->filterBar->getUrlParametersForReset();
        $this->assertArrayNotHasKey('filter', $urlParameters);
    }

    public function testUrlParametersForFilter()
    {
        $urlParameters = $this->filterBar->getUrlParametersForFilter();
        $this->assertCount(1, $urlParameters);
        $this->assertArrayHasKey('filter', $urlParameters);
    }
}
