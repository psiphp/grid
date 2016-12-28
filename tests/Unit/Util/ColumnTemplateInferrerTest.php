<?php

namespace Psi\Component\Grid\Tests\Unit\Util;

use Psi\Component\Grid\Util\ColumnTemplateInferrer;

class ColumnTemplateInferrerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should infer the cell name from a FQ class with a ColumnPreifx
     */
    public function testInferCellTemplate()
    {
        $classFqn = 'Foo\Bar\Column\FoobarColumn';
        $result = ColumnTemplateInferrer::inferCellTemplate($classFqn);

        $this->assertEquals('Foobar', $result);
    }

    /**
     * It should ignore classes not suffixed with Column
     */
    public function testNonSuffixedClass()
    {
        $classFqn = 'Foo\Bar\Column\Foobar';
        $result = ColumnTemplateInferrer::inferCellTemplate($classFqn);

        $this->assertEquals('Foobar', $result);
    }
}
