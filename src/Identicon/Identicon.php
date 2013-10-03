<?php

namespace Identicon;

use Identicon\Identity\Identity;

class Identicon
{

    protected $identity;

    public function __construct($value)
    {
        $this->identity = new Identity($value);
    }

    /**
     * @return Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}