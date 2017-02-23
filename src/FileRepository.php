<?php

namespace Everzet\PersistedObjects;

final class FileRepository implements Repository
{
    private $filename;
    private $identifier;
    private $cache;

    public function __construct($filename, ObjectIdentifier $identifier)
    {
        $this->filename = $filename;
        $this->identifier = $identifier;
    }

    public function save($object)
    {
        $id = $this->stringify($this->getIdentity($object));
        $db = $this->loadDb();
        $db[$id] = $object;
        $this->saveDb($db);
    }

    public function remove($object)
    {
        $id = $this->stringify($this->getIdentity($object));
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
        $this->saveDb(array());
    }

    private function getIdentity($object)
    {
        return $this->identifier->getIdentity($object);
    }

    private function stringify($object)
    {
        return md5(serialize($object));
    }

    private function loadDb()
    {
        if (null === $this->cache) {
            $this->cache = file_exists($this->filename)
                ? unserialize(file_get_contents($this->filename))
                : array();
        }

        return $this->cache;
    }

    private function saveDb(array $collection)
    {
        file_put_contents($this->filename, serialize($collection));
        $this->cache = null;
    }
}
