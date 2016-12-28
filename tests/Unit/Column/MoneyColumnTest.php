<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit\Column;

use Psi\Component\Grid\Column\MoneyColumn;
use Psi\Component\Grid\Column\PropertyColumn;

class MoneyColumnTest extends ColumnTestCase
{
    protected function getColumns(): array
    {
        return [
            new PropertyColumn(),
            new MoneyColumn(),
        ];
    }

    /**
     * It should throw an exception if a non-integer value is provided.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Money column requires an integer value
     */
    public function testNotInteger()
    {
        $data = new \stdClass();
        $data->title = 'foobar';
        $cell = $this->createFactory()->createCell('title', MoneyColumn::class, $data, []);
    }

    /**
     * It should divide the value.
     */
    public function testDivisor()
    {
        $data = new \stdClass();
        $data->money = 2300;
        $cell = $this->createFactory()->createCell('money', MoneyColumn::class, $data, [
            'divisor' => 100,
        ]);
        $this->assertEquals('23.00', $cell->value);
    }

    /**
     * It should scale the value.
     */
    public function testScale()
    {
        $data = new \stdClass();
        $data->money = 2300;
        $cell = $this->createFactory()->createCell('money', MoneyColumn::class, $data, [
            'divisor' => 100,
            'scale' => 4,
        ]);
        $this->assertEquals('23.0000', $cell->value);
    }

    /**
     * It should provide a currency.
     */
    public function testCurrency()
    {
        $data = new \stdClass();
        $data->money = 2300;
        $cell = $this->createFactory()->createCell('money', MoneyColumn::class, $data, [
            'currency' => 'GBP',
        ]);
        $this->assertEquals('GBP', $cell->parameters['currency']);
    }

    /**
     * It should provide a template.
     */
    public function testTemplate()
    {
        $data = new \stdClass();
        $data->money = 2300;
        $cell = $this->createFactory()->createCell('money', MoneyColumn::class, $data, []);
        $this->assertEquals('Money', $cell->getTemplate());
    }
}
