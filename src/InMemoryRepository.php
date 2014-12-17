<?php

namespace Everzet\PersistedObjects;

final class InMemoryRepository implements Repository
{
    private $identityAccessor;
    private $storage = [];

    public function __construct($identityAccessor)
    {
        $this->identityAccessor = $identityAccessor;
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
        if ($this->identityAccessor instanceof \ReflectionMethod) {
            return $this->identityAccessor->invoke($object);
        }
        else if (is_callable($this->identityAccessor)) {
            return call_user_func_array($this->identityAccessor, [$object]);
        }
        else {
            throw new \RuntimeException('Unable to find object identity with provided accessor');
        }
    }

    private function stringify($object)
    {
        return md5(var_export($object, true));
    }
}
