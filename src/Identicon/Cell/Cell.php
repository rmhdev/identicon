<?php

namespace Identicon\Cell;

use Imagine\Image\Point;

final class Cell
{
    private $x;
    private $y;
    private $options;

    public function __construct($x, $y, $options = array())
    {
        $this->x = $x;
        $this->y = $y;
        $this->options = $this->processOptions($options);
    }

    private function processOptions($options = array())
    {
        if (!isset($options["width"])) {
            $options["width"] = 10;
        }
        if (!isset($options["height"])) {
            $options["height"] = $options["width"];
        }
        if (!isset($options["margin"])) {
            $options["margin"] = 0;
        }

        return $options;
    }

    public function getPositionX()
    {
        return $this->x;
    }

    public function getPositionY()
    {
        return $this->y;
    }

    public function getWidth()
    {
        return $this->getOption("width");
    }

    public function getHeight()
    {
        return $this->getOption("height");
    }

    public function getMargin()
    {
        return $this->getOption("margin");
    }

    private function getOption($name, $default = 0)
    {
        if (!isset($this->options[$name])) {
            return $default;
        }

        return $this->options[$name];
    }

    public function getNorth()
    {
        return new Point(
            $this->getStartX() + ($this->getWidth() / 2),
            $this->getStartY()
        );
    }

    private function getStartX()
    {
        return ($this->getPositionX() * $this->getWidth()) + $this->getMargin();
    }

    private function getStartY()
    {
        return ($this->getPositionY() * $this->getHeight()) + $this->getMargin();
    }

    public function getSouth()
    {
        return new Point(
            $this->getStartX() + ($this->getWidth() / 2),
            $this->getStartY() + $this->getHeight()
        );
    }

    public function getEast()
    {
        return new Point(
            $this->getStartX() + $this->getWidth(),
            $this->getStartY() + ($this->getHeight() / 2)
        );
    }

    public function getWest()
    {
        return new Point(
            $this->getStartX(),
            $this->getStartY() + ($this->getHeight() / 2)
        );
    }

    public function getCenter()
    {
        return new Point(
            $this->getStartX() + ($this->getWidth() / 2),
            $this->getStartY() + ($this->getHeight() / 2)
        );
    }

    public function getNorthWest()
    {
        return new Point(
            $this->getStartX(),
            $this->getStartY()
        );
    }

    public function getNorthEast()
    {
        return new Point(
            $this->getStartX() + $this->getWidth(),
            $this->getStartY()
        );
    }

    public function getSouthWest()
    {
        return new Point(
            $this->getStartX(),
            $this->getStartY() + $this->getHeight()
        );
    }

    public function getSouthEast()
    {
        return new Point(
            $this->getStartX() + $this->getWidth(),
            $this->getStartY() + $this->getHeight()
        );
    }
}
