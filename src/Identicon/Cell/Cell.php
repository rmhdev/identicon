<?php

namespace Identicon\Cell;

use Imagine\Image\Point;

class Cell
{
    protected
        $x,
        $y,
        $options;

    public function __construct($x, $y, $options = array())
    {
        $this->x = $x;
        $this->y = $y;
        $this->options = $this->processOptions($options);
    }

    protected function processOptions($options = array())
    {
        if (!isset($options["width"])) {
            $options["width"] = 10;
        }
        if (!isset($options["height"])) {
            $options["height"] = $options["width"];
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
        return $this->options["width"];
    }

    public function getHeight()
    {
        return $this->options["height"];
    }

    public function getNorth()
    {
        return new Point($this->getWidth() / 2, 0);
    }

    public function getSouth()
    {
        return new Point($this->getWidth() / 2, $this->getHeight());
    }
}