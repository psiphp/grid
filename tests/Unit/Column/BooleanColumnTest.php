<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\Column\BooleanColumn;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\View\Cell;

class BooleanColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
            new BooleanColumn(),
        ];
    }

    /**
     * It should set the cell parameters and template.
     */
    public function testParameters()
    {
        $data = new \stdClass();
        $data->title = '1';
        $cell = $this->createFactory()->createCell('title', BooleanColumn::class, $data, []);
        $this->assertEquals('Boolean', $cell->getTemplate());
        $this->assertSame(true, $cell->value);
    }
}
