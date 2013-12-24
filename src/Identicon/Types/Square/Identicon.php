<?php

namespace Identicon\Types\Square;

use Identicon\AbstractIdenticon;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
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
}