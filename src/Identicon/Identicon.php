<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;

class Identicon
{
    const
        MARGIN = 30,
        BLOCK_SIZE = 70,
        BACKGROUND_COLOR = "f0f0f0",
        COLOR = "555555";

    protected
        $identity,
        $image;

    public function __construct($value)
    {
        $this->identity = new Identity($value);
        $this->image = $this->createImage();
        $this->drawIdentity();
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
        return $this->image->get("png");
    }

    protected function createImage()
    {
        $imagine = new Imagine();
        $box = new Box(420, 420);
        $color = new Color(self::BACKGROUND_COLOR);

        return $imagine->create($box, $color);
    }

    protected function drawIdentity()
    {
        $length = $this->getIdentity()->getLength();
        for ($x = 0; $x < $length; $x++) {
            for ($y = 0; $y < $length; $y++) {
                $this->drawBlock($x, $y);
            }
        }
    }

    protected function drawBlock($x, $y)
    {
        if ($this->getIdentity()->getBlock($x, $y)->isColored()) {
            $color = new Color(self::COLOR);
            $startX = self::MARGIN + self::BLOCK_SIZE * $y;
            $startY = self::MARGIN + self::BLOCK_SIZE * $x;
            $this->image->draw()->polygon(array(
                new Point($startX, $startY),
                new Point($startX + self::BLOCK_SIZE, $startY),
                new Point($startX + self::BLOCK_SIZE, $startY + self::BLOCK_SIZE),
                new Point($startX, $startY + self::BLOCK_SIZE)
            ), $color, true);
        }
    }


}