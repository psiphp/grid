<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\View\Cell;
use Psi\Component\Grid\Column\TextColumn;

class TextColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
            new TextColumn(),
        ];
    }

    /**
     * It should set the cell parameters and template.
     */
    public function testParameters()
    {
        $data = new \stdClass();
        $data->title = 'hello world';
        $cell = $this->createFactory()->createCell('title', TextColumn::class, $data, [
            'truncate' => 50,
        ]);
        $this->assertEquals('Text', $cell->getTemplate());
        $this->assertEquals($cell->parameters['truncate'], 50);
    }
}
