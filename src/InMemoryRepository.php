<?php

namespace Everzet\PersistedObjects;

final class InMemoryRepository implements Repository
{
    private $identityLocator;
    private $storage = [];

    public function __construct(ObjectIdentifier $identityLocator)
    {
        $this->identityLocator = $identityLocator;
    }

    public function save($object)
    {
        $id = $this->stringify($this->objectId($object));
        $this->storage[$id] = $object;
    }

    public function remove($object)
    {
        $id = $this->stringify($this->objectId($object));
        unset($this->storage[$id]);
    }

    public function findById($id)
    {
        $id = $this->stringify($id);

        if (!isset($this->storage[$id])) {
            return null;
        }

        return $this->storage[$id];
    }

    public function getAll()
    {
        return array_values($this->storage);
    }

    public function clear()
    {
        $this->storage = [];
    }

    private function objectId($object)
    {
        return $this->identityLocator->getIdentity($object);
    }

    private function stringify($object)
    {
        return md5(var_export($object, true));
    }
}
