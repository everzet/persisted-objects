<?php

namespace Everzet\PersistedObjects;

use ReflectionMethod;

final class FileRepository implements Repository
{
    private $filename;
    private $identityAccessor;

    public function __construct($filename, $identityAccessor)
    {
        $this->filename = $filename;
        $this->identityAccessor = $identityAccessor;
    }

    public function save($object)
    {
        $id = $this->stringify($this->objectId($object));
        $db = $this->loadDb();
        $db[$id] = $object;
        $this->saveDb($db);
    }

    public function remove($object)
    {
        $id = $this->stringify($this->objectId($object));
        $db = $this->loadDb();
        unset($db[$id]);
        $this->saveDb($db);
    }

    public function findById($id)
    {
        $id = $this->stringify($id);
        $db = $this->loadDb();

        if (!isset($db[$id])) {
            return null;
        }

        return $db[$id];
    }

    public function getAll()
    {
        return array_values($this->loadDb());
    }

    public function clear()
    {
        $this->saveDb([]);
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

    private function loadDb()
    {
        return file_exists($this->filename)
            ? unserialize(file_get_contents($this->filename))
            : [];
    }

    private function saveDb(array $collection)
    {
        file_put_contents($this->filename, serialize($collection));
    }
}
