<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Cell;

use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\Tests\Unit\Column\ColumnTestCase;
use Psi\Component\Grid\View\Cell;

class PropertyColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
        ];
    }

    /**
     * It should create a cell from some data.
     */
    public function testCreateCell()
    {
        $object = new \stdClass();
        $object->title = 'barfoo';
        $cell = $this->createFactory()->createCell('title', PropertyColumn::class, $object, []);

        $this->assertInstanceOf(Cell::class, $cell);
        $this->assertEquals('barfoo', $cell->value);
        $this->assertEquals('Property', $cell->template);
    }
}
