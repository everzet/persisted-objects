<?php

use Everzet\PersistedObjects\AccessorObjectIdentifier;

class InMemoryRepositoryTest extends PHPUnit_Framework_TestCase
{

    /** @test */ function shouldBeAbleToSaveAndRetrieveObjectLater()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldBeAbleToSaveAndRetrieveObjectsWithIDAsVO()
    {
        $objectToPersist = new InMemoryObject($objectId = (object)42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldOverrideObjectsWithTheSameId()
    {
        $objectsToPersist = array(
            new InMemoryObject($objectId = 42, 'everzet'),
            new InMemoryObject($objectId, 'marcello')
        );
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $this->assertEquals($objectsToPersist[1], $repository->findById($objectId));
    }

    /** @test */ function shouldBeAbleToRemoveObject()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);
        $repository->remove($objectToPersist);

        $this->assertNull($repository->findById($objectId));
    }

    /** @test */ function shouldDoNothingWhenAskedToRemoveUnstoredObject()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->remove($objectToPersist);

        $this->assertEquals(array(), $repository->getAll());
    }

    /** @test */ function shouldBeAbleToGetAllObjects()
    {
        $objectsToPersist = array(
            new InMemoryObject(42, 'everzet'),
            new InMemoryObject(24, 'marcello')
        );
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $this->assertEquals($objectsToPersist, $repository->getAll());
    }

    /** @test */ function shouldBeAbleToClearRepository()
    {
        $objectsToPersist = array(
            new InMemoryObject(42, 'everzet'),
            new InMemoryObject(24, 'marcello')
        );
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);
        $repository->clear();

        $this->assertEquals(array(), $repository->getAll());
    }

    private function createRepository()
    {
        return new Everzet\PersistedObjects\InMemoryRepository(
            new AccessorObjectIdentifier('getId')
        );
    }
}

class InMemoryObject
{
    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }
}
