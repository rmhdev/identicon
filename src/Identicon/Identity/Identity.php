<?php

namespace Identicon\Identity;

use Identicon\Exception\OutOfBoundsException;

class Identity
{

    protected
        $identification,
        $matrix;

    public function __construct($identification)
    {
        $this->identification = $identification;
        $this->updateMatrix();
    }

    protected function updateMatrix()
    {
        $matrix = array();
        for ($x = 0; $x < $this->getLength(); $x++) {
            for ($y = 0; $y < $this->getLength(); $y++) {
                if (!isset($matrix[$x])) {
                    $matrix[$x] = array();
                }
                $matrix[$x][$y] = $this->createBlock($x, $y);
            }
        }
        $this->matrix = $matrix;
    }

    protected function createBlock($posX, $posY)
    {
        // TODO: generate deterministic values.
        $isColored = false;
        if (($posX == 0) && ($this->identification[0] == "m")) {
            $isColored = true;
        }

        return new Block($isColored);
    }

    public function getLength()
    {
        return 5;
    }

    public function getPosition($posX, $posY)
    {
        if ($this->isOutOfBounds($posX, $posY)) {
            throw new OutOfBoundsException();
        }

        return new Block();
    }

    protected function isOutOfBounds($posX, $posY)
    {
        return $posX < 0 || $posY < 0 || ($posX >= $this->getLength()) || ($posY >= $this->getLength());
    }

    public function __toString()
    {
        $result = array();
        for ($x = 0; $x < $this->getLength(); $x++) {
            $row = "";
            for ($y = 0; $y < $this->getLength(); $y++) {
                $row .= $this->getPosition($x, $y)->__toString();
            }
            $result[] = $row;
        }

        return implode("/", $result);
    }


}