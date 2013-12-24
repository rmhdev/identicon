<?php

namespace Identicon\Types\Circle;

use Identicon\AbstractIdenticon;
use Imagine\Image\Box;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
    protected function drawBlock($x, $y)
    {
        $margin = $this->getOption("margin");
        $blockSize = $this->getOption("block-size");
        $centerX = $margin + $blockSize * $y + ($blockSize / 2);
        $centerY = $margin + $blockSize * $x + ($blockSize / 2);
        $this->image->draw()->ellipse(
            new Point($centerX, $centerY),
            new Box($blockSize, $blockSize),
            $this->getColor(),
            true
        );
    }
}