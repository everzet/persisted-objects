<?php

namespace Everzet\PersistedObjects;

final class InMemoryRepository implements Repository
{
    private $identifier;
    private $storage = array();

    public function __construct(ObjectIdentifier $identifier)
    {
        $this->identifier = $identifier;
    }

    public function save($object)
    {
        $id = $this->stringify($this->getIdentity($object));
        $this->storage[$id] = $object;
    }

    public function remove($object)
    {
        $id = $this->stringify($this->getIdentity($object));
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
        $this->storage = array();
    }

    private function getIdentity($object)
    {
        return $this->identifier->getIdentity($object);
    }

    private function stringify($object)
    {
        return md5(serialize($object));
    }
}
