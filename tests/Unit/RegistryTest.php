<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\Registry;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should throw an exception when registering a class
     * which is not a subclass of the required class.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Expected an instance of
     */
    public function testRegisterNotInstance()
    {
        $this->create(\stdClass::class, 'standard')->register(new \DateTime());
    }

    /**
     * It should throw an exception if the instance is not an object.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Expected an object, but got
     */
    public function testInstanceNotObject()
    {
        $this->create(\stdClass::class, 'standard')->register('asd');
    }

    /**
     * It should throw an exception if the instance is already registered.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Foobar "stdClass" has already been registered
     */
    public function testRegisterAgain()
    {
        $registry = $this->create(\stdClass::class, 'Foobar');
        $registry->register(new \stdClass());
        $registry->register(new \stdClass());
    }

    /**
     * It should register and a class with an alias.
     */
    public function testRegisterAlias()
    {
        $service = new \stdClass();
        $registry = $this->create(\stdClass::class, 'Foobar');
        $registry->register($service, 'std');
        $this->assertSame($service, $registry->get('std'));
    }

    /**
     * It should register a class.
     */
    public function testRegisterClass()
    {
        $service = new \stdClass();
        $registry = $this->create(\stdClass::class, 'Foobar');
        $registry->register($service);
        $this->assertSame($service, $registry->get(\stdClass::class));
    }

    /**
     * It should say if it has a class or not.
     */
    public function testHasClass()
    {
        $registry = $this->create(\stdClass::class, 'std');
        $registry->register(new \stdClass(), 'std');
        $this->assertTrue($registry->has(\stdClass::class));
        $this->assertFalse($registry->has('somethingElse'));
    }

    /**
     * It should say if it has an alias or not.
     */
    public function testHasAlias()
    {
        $registry = $this->create(\stdClass::class, 'std');
        $registry->register(new \stdClass(), 'std');
        $this->assertTrue($registry->has('std'));
    }

    public function create($class, $context)
    {
        return new Registry($class, $context);
    }
}
