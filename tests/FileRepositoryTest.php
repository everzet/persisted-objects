<?php

class FileRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $filename;

    protected function setUp()
    {
        $this->filename = sys_get_temp_dir() . '/test_repository';
    }

    protected function tearDown()
    {
        file_exists($this->filename) && unlink($this->filename);
    }

    /** @test */ function shouldBeAbleToSaveAndRetrieveObjectLater()
    {
        $objectToPersist = new PersistedObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldBeAbleToSaveAndRetrieveObjectsWithIDAsVO()
    {
        $objectToPersist = new PersistedObject($objectId = (object)42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldOverrideObjectsWithTheSameId()
    {
        $objectsToPersist = [
            new PersistedObject($objectId = 42, 'everzet'),
            new PersistedObject($objectId, 'marcello')
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
        $objectToPersist = new PersistedObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->save($objectToPersist);
        $repository->remove($objectToPersist);

        $this->assertNull($repository->findById($objectId));
    }

    /** @test */ function shouldDoNothingWhenAskedToRemoveUnstoredObject()
    {
        $objectToPersist = new PersistedObject($objectId = 42, 'everzet');
        $repository = $this->createRepository();

        $repository->remove($objectToPersist);

        $this->assertEquals([], $repository->getAll());
    }

    /** @test */ function shouldBeAbleToGetAllObjects()
    {
        $objectsToPersist = [
            new PersistedObject(42, 'everzet'),
            new PersistedObject(24, 'marcello')
        ];
        $repository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $newRepository = $this->createRepository();
        $this->assertEquals($objectsToPersist, $repository->getAll());
        $this->assertEquals($repository->getAll(), $newRepository->getAll());
    }

    /** @test */ function shouldPersistObjectsBetweenInstances()
    {
        $objectsToPersist = [
            new PersistedObject(42, 'everzet'),
            new PersistedObject(24, 'marcello')
        ];
        $repository = $this->createRepository();
        $newRepository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);

        $this->assertEquals($repository->getAll(), $newRepository->getAll());
    }

    /** @test */ function shouldBeAbleToClearRepository()
    {
        $objectsToPersist = [
            new PersistedObject(42, 'everzet'),
            new PersistedObject(24, 'marcello')
        ];
        $repository = $this->createRepository();
        $newRepository = $this->createRepository();

        $repository->save($objectsToPersist[0]);
        $repository->save($objectsToPersist[1]);
        $repository->clear();

        $this->assertEquals([], $repository->getAll());
        $this->assertEquals($newRepository->getAll(), $repository->getAll());
    }

    /** @test */ function shouldBeAbleToUseCallbackAsIdentityLocator()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = new Everzet\PersistedObjects\FileRepository(
            $this->filename,
            function (InMemoryObject $object) { return $object->getId(); }
        );

        $repository->save($objectToPersist);

        $this->assertEquals($objectToPersist, $repository->findById($objectId));
    }

    /** @test */ function shouldErrorWhenGivenOtherIdentityLocator()
    {
        $objectToPersist = new InMemoryObject($objectId = 42, 'everzet');
        $repository = new Everzet\PersistedObjects\FileRepository($this->filename, 'bar');

        $this->setExpectedException('RuntimeException');

        $repository->save($objectToPersist);
    }

    private function createRepository()
    {
        return new Everzet\PersistedObjects\FileRepository(
            $this->filename,
            new ReflectionMethod('PersistedObject', 'getId')
        );
    }
}

class PersistedObject
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
