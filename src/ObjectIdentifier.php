<?php

namespace Everzet\PersistedObjects;

interface ObjectIdentifier
{
    public function getIdentity($object);
}
