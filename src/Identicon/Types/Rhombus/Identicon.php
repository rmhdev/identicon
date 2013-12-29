<?php

namespace Identicon\Types\Rhombus;

use Identicon\AbstractIdenticon;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
    protected function drawBlock($x, $y)
    {
        parent::drawBlock($x, $y);
        $this->image->draw()->polygon(
            $this->calculateRhombusCoordinates($x, $y),
            $this->getColor()->lighten(10),
            true
        );
    }

    protected function calculateRhombusCoordinates($x, $y)
    {
        $cell = $this->getCell($x, $y);
        return array(
            $cell->getNorth(),
            $cell->getEast(),
            $cell->getSouth(),
            $cell->getWest()
        );
    }


}