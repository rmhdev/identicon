<?php

namespace Identicon\Types\Square;

use Identicon\AbstractIdenticon;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
    protected function drawBlock($x, $y)
    {
        $this->image->draw()->polygon(
            $this->calculatePolygonCoordinates($x, $y),
            $this->getColor(),
            true
        );
    }

    protected function calculatePolygonCoordinates($x, $y)
    {
        $cell = $this->getCell($x, $y);

        return array(
            $cell->getNorthWest(),
            $cell->getNorthEast(),
            $cell->getSouthEast(),
            $cell->getSouthWest(),
        );
    }
}