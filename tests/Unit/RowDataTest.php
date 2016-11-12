<?php

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\RowData;

class RowDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should throw an exception if trying to create rows data from a non-object.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Object must be an object, got: "string"
     */
    public function testCreateFromNonObject()
    {
        RowData::fromObject('hello');
    }

    public function testReturnObject()
    {
        $object = new \stdClass();
        $data = RowData::fromObject($object);
        $this->assertEquals($object, $data->getObject());
    }
}
