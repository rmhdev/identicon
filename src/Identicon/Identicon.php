<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

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

    public function getContent()
    {
        $imagine = new Imagine();
        $box = new Box(420, 420);
        $image = $imagine->create($box);

        return $image->get("png");
    }
}