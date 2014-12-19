<?php

namespace Everzet\PersistedObjects;

use ReflectionMethod;

final class FileRepository implements Repository
{
    private $filename;
    private $identityLocator;

    public function __construct($filename, IdentityLocator $identityLocator)
    {
        $this->filename = $filename;
        $this->identityLocator = $identityLocator;
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
        return $this->identityLocator->getIdentity($object);
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
