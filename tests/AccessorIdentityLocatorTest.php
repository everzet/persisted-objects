<?php

namespace IdentityAccessorTest;

use Everzet\PersistedObjects\AccessorIdentityLocator;
use Everzet\PersistedObjects\IdentityLocator;

class AccessorIdentityLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdentityLocator
     */
    private $accessor;

    function setUp()
    {
        $this->accessor = new AccessorIdentityLocator('getId');
    }

    /** @test */ function shouldBeAnIdentityAccessor()
    {
        $this->assertInstanceOf('Everzet\PersistedObjects\IdentityLocator', $this->accessor);
    }

    /** @test */ function shouldReturnTheRightCallback()
    {
        $obj = $this->getMock('IdentityAccessorTest\AccessorIdentifiedObject');
        $obj->method('getId')->willReturn(1234);

        $id = $this->accessor->getIdentity($obj);
        $this->assertEquals(1234, $id);
    }

    /** @test @expectedException Exception */ function shouldThrowIfMethodDoesNotExist()
    {
        $this->accessor = new AccessorIdentityLocator('getOtherId', 'specificObject');

        $obj = $this->getMock('IdentityAccessorTest\AccessorIdentifiedObject');

        $this->accessor->getIdentity($obj);
    }
}

interface AccessorIdentifiedObject
{
    public function getId();
}