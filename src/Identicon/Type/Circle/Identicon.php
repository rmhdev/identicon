<?php

namespace Identicon\Type\Circle;

use Identicon\AbstractIdenticon;
use Identicon\IdenticonInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

final class Identicon extends AbstractIdenticon implements IdenticonInterface
{
    protected function drawBlock($x, $y)
    {
        $size = $this->getOption("block-size") / 1.75;
        parent::drawBlock($x, $y);
        $this->getImage()->draw()->ellipse(
            $this->getCell($x, $y)->getCenter(),
            new Box($size, $size),
            $this->getColor()->lighten(10),
            true
        );
    }
}
