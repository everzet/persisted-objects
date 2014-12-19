<?php

namespace IdentityAccessorTest;

use Everzet\PersistedObjects\ObjectIdentifier;
use Everzet\PersistedObjects\CallbackObjectIdentifier;

class CallbackIdentityLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectIdentifier
     */
    private $accessor;

    function setUp()
    {
        $callback = function ($obj) {
            return 1234;
        };
        $this->accessor = new CallbackObjectIdentifier($callback);
    }

    /** @test */
    function shouldBeAnIdentityAccessor()
    {
        $this->assertInstanceOf('Everzet\PersistedObjects\ObjectIdentifier', $this->accessor);
    }

    /** @test */
    function shouldReturnTheRightCallback()
    {
        $id = $this->accessor->getIdentity(new \StdClass);
        $this->assertEquals(1234, $id);
    }
}