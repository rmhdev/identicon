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
        MARGIN = 35,
        BLOCK_SIZE = 70,
        BACKGROUND_COLOR = "f0f0f0";

    protected
        $identity,
        $image,
        $backgroundColor,
        $margin;

    protected static $colorPalette = array(
        "AE6A5B", "AE945B", "9FAE5B", "75AE5B", "5BAE6A", "5BAE94", "5B9FAE", "5B75AE",
        "6A5BAE", "945BAE", "AE5B9F", "AE5B75", "C28F84", "D7B5AD", "84B7C2", "#555555"
    );

    public function __construct($value, $backgroundColor = NULL, $margin = NULL)
    {
        $this->identity = new Identity($value);
        $this->backgroundColor = $backgroundColor ? $backgroundColor : self::BACKGROUND_COLOR;
        $this->margin = is_null($margin) ? self::MARGIN : $margin;
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
        $size = $this->margin * 2 + $this->getIdentity()->getLength() * self::BLOCK_SIZE;
        $imagine = new Imagine();
        $box = new Box($size, $size);

        return $imagine->create($box, $this->getBackgroundColor());
    }

    public function getBackgroundColor()
    {
        return new Color($this->backgroundColor);
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

    public function getWidth()
    {
        return $this->image->getSize()->getWidth();
    }

    public function getHeight()
    {
        return $this->image->getSize()->getHeight();
    }

}