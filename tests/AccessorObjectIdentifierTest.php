<?php

namespace IdentityAccessorTest;

use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\ObjectIdentifier;

class AccessorObjectIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectIdentifier
     */
    private $accessor;

    function setUp()
    {
        $this->accessor = new AccessorObjectIdentifier('getId');
    }

    /** @test */ function shouldBeAnIdentityAccessor()
    {
        $this->assertInstanceOf('Everzet\PersistedObjects\ObjectIdentifier', $this->accessor);
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
        $this->accessor = new AccessorObjectIdentifier('getOtherId', 'specificObject');

        $obj = $this->getMock('IdentityAccessorTest\AccessorIdentifiedObject');

        $this->accessor->getIdentity($obj);
    }
}

interface AccessorIdentifiedObject
{
    public function getId();
}
