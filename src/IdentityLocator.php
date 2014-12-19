<?php

namespace Everzet\PersistedObjects;

interface IdentityLocator
{
    public function getIdentity($obj);
} 