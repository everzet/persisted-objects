<?php

namespace Everzet\PersistedObjects;

interface Repository
{
    public function save($object);
    public function remove($object);
    public function findById($id);
    public function getAll();
    public function clear();
}
