<?php

namespace Identicon\Identity;

class Identity
{
    public function getLength()
    {
        return 5;
    }

    public function getPosition($posX, $posY)
    {
        return new Block();
    }
}