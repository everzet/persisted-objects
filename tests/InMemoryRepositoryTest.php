<?php

class InMemoeryRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $filename;

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
        $objectsToPersist = [
            new InMemoryObject($objectId = 42, 'everzet'),
            new InMemoryObject($objectId, 'marcello')
        ];
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $this->assertEquals($objectsToPersist[1], $repository->findById($objectId));
    }

    /** @test @expectedException Exception */
    function shouldThrowAnExceptionIfUnexpectedObjectGiven()
    {
        $objectToPersist = (object)[];
        $repository = $this->createRepository();

        $repository->save($objectToPersist);
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

        $this->assertEquals([], $repository->getAll());
    }

    /** @test */ function shouldBeAbleToGetAllObjects()
    {
        $objectsToPersist = [
            new InMemoryObject(42, 'everzet'),
            new InMemoryObject(24, 'marcello')
        ];
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $this->assertEquals($objectsToPersist, $repository->getAll());
    }

    /** @test */ function shouldBeAbleToClearRepository()
    {
        $objectsToPersist = [
            new InMemoryObject(42, 'everzet'),
            new InMemoryObject(24, 'marcello')
        ];
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);
        $repository->clear();

        $this->assertEquals([], $repository->getAll());
    }

    /** @test */ function shouldBeAbleToUseCallbackAsIdentityLocator()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = new Everzet\PersistedObjects\InMemoryRepository(
            function (InMemoryObject $object) { return $object->getId(); }
        );

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldErrorWhenGivenOtherIdentityLocator()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = new Everzet\PersistedObjects\InMemoryRepository('bar');

        $this->setExpectedException('RuntimeException');

        $repository->save($objectToPersist);
    }

    private function createRepository()
    {
        return new Everzet\PersistedObjects\InMemoryRepository(
            new ReflectionMethod('InMemoryObject', 'getId')
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
