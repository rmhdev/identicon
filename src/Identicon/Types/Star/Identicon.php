<?php

namespace Identicon\Types\Star;

use Identicon\AbstractIdenticon;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
    protected function drawBlock($x, $y)
    {
        $this->image->draw()->polygon(
            $this->calculateFirstTriangleCoordinates($x, $y),
            $this->getColor(),
            true
        );

        $this->image->draw()->polygon(
            $this->calculateSecondTriangleCoordinates($x, $y),
            $this->getColor(),
            true
        );
    }

    protected function calculateFirstTriangleCoordinates($x, $y)
    {
        $margin = $this->getOption("margin");
        $blockSize = $this->getOption("block-size");
        $startX = $margin + $blockSize * $y;
        $startY = $margin + $blockSize * $x;
        return array(
            new Point($startX + ($blockSize / 2), $startY),
            new Point($startX + $blockSize, $startY + ($blockSize / 4) * 3),
            new Point($startX, $startY + ($blockSize / 4) * 3)
        );
    }

    protected function calculateSecondTriangleCoordinates($x, $y)
    {
        $margin = $this->getOption("margin");
        $blockSize = $this->getOption("block-size");
        $startX = $margin + $blockSize * ($y + 1);
        $startY = $margin + $blockSize * $x + ($blockSize / 4);
        return array(
            new Point($startX, $startY),
            new Point($startX - ($blockSize / 2), $margin + $blockSize * ($x + 1)),
            new Point(($startX - $blockSize), $startY)
        );
    }
}