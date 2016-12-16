<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\Column\SelectColumn;
use Psi\Component\Grid\View\Cell;

class SelectColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
            new SelectColumn(),
        ];
    }

    /**
     * It should create a cell from some data.
     */
    public function testCreateCell()
    {
        $object = new \stdClass();
        $object->id = 1234;
        $cell = $this->createFactory()->createCell('select', SelectColumn::class, $object, []);

        $this->assertInstanceOf(Cell::class, $cell);
        $this->assertEquals(1234, $cell->value);
        $this->assertEquals('Select', $cell->template);
    }
}
