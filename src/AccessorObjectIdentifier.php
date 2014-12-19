<?php

namespace Everzet\PersistedObjects;

final class AccessorObjectIdentifier implements ObjectIdentifier
{
    private $accessor;

    public function __construct($accessor)
    {
        $this->accessor = $accessor;
    }

    public function getIdentity($obj)
    {
        if (!method_exists($obj, $this->accessor)) {
            throw new \RuntimeException(sprintf('Object of type %s does not have accessor %s', get_class($obj), $this->accessor));
        }

        return $obj->{$this->accessor}();
    }
}
