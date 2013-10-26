<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class Identicon
{
    const
        MARGIN = 30,
        BLOCK_SIZE = 70;

    protected
        $identity,
        $image;

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
        return $this->createImage()->get("png");
    }

    protected function createImage()
    {
        $imagine = new Imagine();
        $box = new Box(420, 420);
        $image = $imagine->create($box);

        return $this->drawIdentity($image);
    }

    protected function drawIdentity(ImageInterface $image)
    {
        $length = $this->getIdentity()->getLength();
        for ($x = 0; $x < $length; $x++) {
            for ($y = 0; $y < $length; $y++) {
                $this->drawBlock($image, $x, $y);
            }
        }

        return $image;
    }

    protected function drawBlock(ImageInterface $image, $x, $y)
    {
        if ($this->getIdentity()->getBlock($x, $y)->isColored()) {
            $color = new Color("555555");
            $startX = self::MARGIN + self::BLOCK_SIZE * $y;
            $startY = self::MARGIN + self::BLOCK_SIZE * $x;
            $image->draw()->polygon(array(
                new Point($startX, $startY),
                new Point($startX + self::BLOCK_SIZE, $startY),
                new Point($startX + self::BLOCK_SIZE, $startY + self::BLOCK_SIZE),
                new Point($startX, $startY + self::BLOCK_SIZE)
            ), $color, true);
        }
    }


}