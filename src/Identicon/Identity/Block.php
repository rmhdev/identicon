<?php

namespace Identicon\Identity;

final class Block
{
    protected $isColored;

    public function __construct($isColored = false)
    {
        $this->isColored = $isColored;
    }

    public function isColored()
    {
        return $this->isColored;
    }

    public function __toString()
    {
        return $this->isColored() ? "#" : " ";
    }
}
