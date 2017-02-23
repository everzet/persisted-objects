<?php

namespace Everzet\PersistedObjects;

final class CallbackObjectIdentifier implements ObjectIdentifier
{
    private $callable;

    public function __construct($callable)
    {
        $this->callable = $callable;
    }

    public function getIdentity($object)
    {
        return call_user_func($this->callable, $object);
    }
}
