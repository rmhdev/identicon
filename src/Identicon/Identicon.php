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
        BACKGROUND_COLOR = "f0f0f0";

    protected
        $identity,
        $image;

    protected static $colorPalette = array(
        "AE6A5B", "AE945B", "9FAE5B", "75AE5B", "5BAE6A", "5BAE94", "5B9FAE", "5B75AE",
        "6A5BAE", "945BAE", "AE5B9F", "AE5B75", "C28F84", "D7B5AD", "84B7C2", "#555555"
    );

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

        return $imagine->create($box, $this->getBackgroundColor());
    }

    public function getBackgroundColor()
    {
        return new Color(self::BACKGROUND_COLOR);
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
            $this->image->draw()->polygon(
                $this->calculatePolygonCoordinates($x, $y),
                $this->getColor(),
                true
            );
        }
    }

    protected function calculatePolygonCoordinates($x, $y)
    {
        $startX = self::MARGIN + self::BLOCK_SIZE * $y;
        $startY = self::MARGIN + self::BLOCK_SIZE * $x;
        return array(
            new Point($startX, $startY),
            new Point($startX + self::BLOCK_SIZE, $startY),
            new Point($startX + self::BLOCK_SIZE, $startY + self::BLOCK_SIZE),
            new Point($startX, $startY + self::BLOCK_SIZE)
        );
    }

    public function getColor()
    {
        return new Color($this->calculateColorFromPalette($this->getIdentity()->getCode()));
    }

    protected function calculateColorFromPalette($code)
    {
        $colorPalettePosition = hexdec($code[0]);

        return self::$colorPalette[$colorPalettePosition];
    }

}