<?php

namespace Everzet\PersistedObjects;

final class AccessorObjectIdentifier implements ObjectIdentifier
{
    private $accessor;

    public function __construct($accessor)
    {
        $this->accessor = $accessor;
    }

    public function getIdentity($object)
    {
        if (!method_exists($object, $this->accessor)) {
            throw new \RuntimeException(sprintf('Object of type %s does not have accessor %s', get_class($object), $this->accessor));
        }

        return $object->{$this->accessor}();
    }
}
