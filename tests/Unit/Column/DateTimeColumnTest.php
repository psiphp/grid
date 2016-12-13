<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\Column\DateTimeColumn;
use Psi\Component\Grid\Column\PropertyColumn;
use Psi\Component\Grid\View\Cell;

class DateTimeColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
            new DateTimeColumn(),
        ];
    }

    /**
     * It should throw an exception if a non-datetime value is provided.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The DateTime column requires a \DateTime value at "title", but got "string"
     */
    public function testNotDateTime()
    {
        $data = new \stdClass();
        $data->title = 'foobar';
        $cell = $this->createFactory()->createCell('title', DateTimeColumn::class, $data, []);
    }

    /**
     * It should set the cell parameters and template.
     */
    public function testParameters()
    {
        $data = new \stdClass();
        $data->title = new \DateTime('2016-01-01');
        $cell = $this->createFactory()->createCell('title', DateTimeColumn::class, $data, []);
        $this->assertEquals('c', $cell->parameters['format']);
        $this->assertEquals('DateTime', $cell->template);
    }
}
