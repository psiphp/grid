<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\RowData;

class RowDataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->arrayAccess = $this->prophesize(\ArrayAccess::class);
    }

    /**
     * It should throw an exception if trying to create rows data from a non-object, non array and non arrayaccess.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Row data must be either an object, an array, or it must implement ArrayAccess. Got "string
     */
    public function testCreateFromNonObject()
    {
        new RowData('hello');
    }

    /**
     * It should say if the data has an array nature.
     */
    public function testArrayLike()
    {
        $data = new RowData($this->arrayAccess->reveal());
        $this->assertTrue($data->isArrayLike());

        $data = new RowData([]);
        $this->assertTrue($data->isArrayLike());

        $data = new RowData(new \stdClass());
        $this->assertFalse($data->isArrayLike());
    }

    /**
     * It should provide a magic __get method.
     */
    public function testMagicGet()
    {
        $data = new RowData(['foobar' => 'barfoo']);
        $this->assertEquals('barfoo', $data->foobar);
    }

    /**
     * It should throw an exception if calling __get when the data
     * is not an array.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Magic __get
     */
    public function testMagicGetNotArray()
    {
        $data = new RowData(new \stdClass());
        $data->foobar;
    }

    /**
     * It should throw an exception if the magic __get property does
     * not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown property
     */
    public function testMagicGetNotExist()
    {
        $data = new RowData([]);
        $data->foobar;
    }


    public function testReturnObject()
    {
        $object = new \stdClass();
        $data = new RowData($object);
        $this->assertEquals($object, $data->getData());
    }
}
