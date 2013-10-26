<?php

namespace Identicon;

use Identicon\Exception\InvalidArgumentException;
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
        $this->checkValueNumeric($options["margin"]);

        if (!isset($options["block-size"])) {
            $options["block-size"] = self::BLOCK_SIZE;
        }
        $this->checkValueNumeric($options["block-size"]);

        if (!isset($options["background-color"])) {
            $options["background-color"] = self::BACKGROUND_COLOR;
        }
        $this->checkValueBackgroundColor($options["background-color"]);

        return $options;
    }

    protected function checkValueNumeric($value)
    {
        if ((!is_int($value)) || ($value < 0)) {
            throw new InvalidArgumentException("Negative values are not allowed");
        }
        return $value;
    }

    protected function checkValueBackgroundColor($backgroundColor)
    {
        try {
            $colorObject = new Color($backgroundColor);
        } catch (\Imagine\Exception\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e);
        }
        return $backgroundColor;
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
        $size = $this->calculateSize();
        $imagine = new Imagine();
        $box = new Box($size, $size);

        return $imagine->create($box, $this->getBackgroundColor());
    }

    protected function calculateSize()
    {
        return ($this->getOption("margin") * 2) +
            ($this->getIdentity()->getLength() * $this->getOption("block-size"));
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
        $blockSize = $this->getOption("block-size");
        $startX = $margin + $blockSize * $y;
        $startY = $margin + $blockSize * $x;
        return array(
            new Point($startX, $startY),
            new Point($startX + $blockSize, $startY),
            new Point($startX + $blockSize, $startY + $blockSize),
            new Point($startX, $startY + $blockSize)
        );
    }

    public function getColor()
    {
        return new Color($this->calculateColorFromPalette($this->getIdentity()->getCode()));
    }

    protected function calculateColorFromPalette($code)
    {
        $colorPalettePosition = hexdec($code[0]);

        return self::$colorPalette[$colorPalettePosition % sizeof(self::$colorPalette)];
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