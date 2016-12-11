<?php

declare(strict_types=1);

namespace Psi\Component\Grid\Tests\Unit;

use Psi\Component\Grid\ActionResponse;

class ActionResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It can be instantiated with an empty array.
     */
    public function testResponse()
    {
        $response = ActionResponse::create([]);
        $this->assertInstanceOf(ActionResponse::class, $response);
        $this->assertEquals(0, $response->getAffectedRecordCount());
        $this->assertFalse($response->hasErrors());
        $this->assertFalse($response->hasRedirect());
        $this->assertEquals([], $response->getRedirectParams());
    }

    /**
     * It should say if it has errors.
     */
    public function testHasErrors()
    {
        $response = ActionResponse::create([
            'errors' => ['yes '],
        ]);
        $this->assertTrue($response->hasErrors());
    }

    /**
     * It should throw an exception if the number of records affected is negative.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage cannot be negative
     */
    public function testNegativeAffected()
    {
        ActionResponse::create([
            'affected' => -10,
        ]);
    }

    /**
     * It should throw an exception if invalid keys are given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unexpected keys for action response: "foobar", valid keys:
     */
    public function testInvalidKeys()
    {
        ActionResponse::create([
            'foobar' => 'barfoo',
        ]);
    }

    /**
     * It should say if it has a redirect.
     */
    public function testRedirect()
    {
        $response = ActionResponse::create([
            'redirect' => 'foobar',
            'redirectParams' => ['foobar' => 123],
        ]);

        $this->assertTrue($response->hasRedirect());
        $this->assertEquals(['foobar' => 123], $response->getRedirectParams());
    }
}
