<?php

namespace Identicon\Type\Pyramid;

use Identicon\AbstractIdenticon;
use Identicon\IdenticonInterface;
use Imagine\Image\Point;

final class Identicon extends AbstractIdenticon implements IdenticonInterface
{
    protected function drawBlock($x, $y)
    {
        parent::drawBlock($x, $y);
        $this->getImage()->draw()->polygon(
            $this->calculateUpCoordinates($x, $y),
            $this->getColor()->lighten(10),
            true
        );
        $this->getImage()->draw()->polygon(
            $this->calculateDownCoordinates($x, $y),
            $this->getColor()->lighten(5),
            true
        );
        $this->getImage()->draw()->polygon(
            $this->calculateLeftCoordinates($x, $y),
            $this->getColor()->lighten(15),
            true
        );
    }

    protected function calculateUpCoordinates($x, $y)
    {
        $cell = $this->getCell($x, $y);
        return array(
            $cell->getNorthWest(),
            $cell->getNorthEast(),
            $cell->getCenter()
        );
    }

    protected function calculateDownCoordinates($x, $y)
    {
        $cell = $this->getCell($x, $y);
        return array(
            $cell->getCenter(),
            $cell->getSouthEast(),
            $cell->getSouthWest(),
        );
    }

    protected function calculateLeftCoordinates($x, $y)
    {
        $cell = $this->getCell($x, $y);
        return array(
            $cell->getNorthWest(),
            $cell->getCenter(),
            $cell->getSouthWest(),
        );
    }
}
