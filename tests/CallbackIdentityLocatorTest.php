<?php

namespace IdentityAccessorTest;

use Everzet\PersistedObjects\IdentityLocator;
use Everzet\PersistedObjects\CallbackIdentityLocator;

class CallbackIdentityLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdentityLocator
     */
    private $accessor;

    function setUp()
    {
        $callback = function($obj) { return 1234; };
        $this->accessor = new CallbackIdentityLocator($callback);
    }

    /** @test */ function shouldBeAnIdentityAccessor()
    {
        $this->assertInstanceOf('Everzet\PersistedObjects\IdentityLocator', $this->accessor);
    }

    /** @test */ function shouldReturnTheRightCallback()
    {
        $id = $this->accessor->getIdentity(new \StdClass);
        $this->assertEquals(1234, $id);
    }
} 