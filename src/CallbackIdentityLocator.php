<?php

namespace Everzet\PersistedObjects;

class CallbackIdentityLocator implements IdentityLocator
{
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function getIdentity($obj)
    {
        return call_user_func($this->callable, $obj);
    }
}
