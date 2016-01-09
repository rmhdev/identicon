<?php

namespace Identicon\Type\Rhombus;

use Identicon\AbstractIdenticon;
use Identicon\IdenticonInterface;

final class Identicon extends AbstractIdenticon implements IdenticonInterface
{
    protected function drawBlock($x, $y)
    {
        parent::drawBlock($x, $y);
        $this->getImage()->draw()->polygon(
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
