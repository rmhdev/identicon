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
        $options;

    protected static $colorPalette = array(
        "AE6A5B", "AE945B", "9FAE5B", "75AE5B", "5BAE6A", "5BAE94", "5B9FAE", "5B75AE",
        "6A5BAE", "945BAE", "AE5B9F", "AE5B75", "C28F84", "D7B5AD", "84B7C2", "#555555"
    );

    public function __construct($value, $options = array())
    {
        $this->options = $this->processOptions($options);
        $this->identity = new Identity($value);
        $this->image = $this->createImage();
        $this->drawIdentity();
    }

    protected function processOptions($options = array())
    {
        if (!isset($options["margin"])) {
            $options["margin"] = self::MARGIN;
        }
        if (!isset($options["background-color"])) {
            $options["background-color"] = self::BACKGROUND_COLOR;
        }

        return $options;
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
        $size = $this->getOption("margin") * 2 + $this->getIdentity()->getLength() * self::BLOCK_SIZE;
        $imagine = new Imagine();
        $box = new Box($size, $size);

        return $imagine->create($box, $this->getBackgroundColor());
    }

    protected function getOption($name)
    {
        if (!isset($this->options[$name])) {
            return NULL;
        }

        return $this->options[$name];
    }

    public function getBackgroundColor()
    {
        return new Color($this->getOption("background-color"));
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
        $margin = $this->getOption("margin");
        $startX = $margin + self::BLOCK_SIZE * $y;
        $startY = $margin + self::BLOCK_SIZE * $x;
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