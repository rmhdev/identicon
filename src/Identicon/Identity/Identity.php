<?php

namespace Identicon\Identity;

use Identicon\Exception\OutOfBoundsException;

class Identity
{

    protected
        $identification,
        $hash,
        $blocks;

    public function __construct($identification)
    {
        $this->prepareIdentification($identification);
        $this->initializeBlocks();
    }

    protected function prepareIdentification($identification)
    {
        $this->identification = mb_convert_case($identification, MB_CASE_LOWER, "utf8");
        $this->hash = sha1($this->identification);
    }

    protected function initializeBlocks()
    {
        $this->blocks = array();
        for ($x = 0; $x < $this->getLength(); $x++) {
            for ($y = 0; $y < $this->getLength(); $y++) {
                $this->insertBlock($x, $y);
            }
        }
    }

    protected function insertBlock($x, $y)
    {
        if (!isset($this->blocks[$x])) {
            $this->blocks[$x] = array();
        }
        $this->blocks[$x][$y] = $this->createBlock($x, $y);
    }

    protected function createBlock($posX, $posY)
    {
        return new Block($this->isPositionColored($posX, $posY));
    }

    protected function isPositionColored($posX, $posY)
    {
        // value is hexadecimal number: [0..7] [8..15]
        return $this->calculateValueForPosition($posX, $posY) >= 8 ? true : false;
    }

    protected function calculateValueForPosition($posX, $posY)
    {
        return hexdec(substr($this->hash, $this->calculateCharPosition($posX, $posY), 1));
    }

    protected function calculateCharPosition($posX, $posY)
    {
        if ($posY > $this->getLength() / 2) {
            $posY = $this->getLength() - $posY - 1;
        }

        return $posX * $this->getLength() + $posY;
    }

    public function getLength()
    {
        return 5;
    }

    /**
     * @param $posX
     * @param $posY
     * @return Block
     * @throws OutOfBoundsException
     */
    public function getBlock($posX, $posY)
    {
        if ($this->isOutOfBounds($posX, $posY)) {
            throw new OutOfBoundsException();
        }

        return $this->blocks[$posX][$posY];
    }

    protected function isOutOfBounds($posX, $posY)
    {
        return $posX < 0 || $posY < 0 || ($posX >= $this->getLength()) || ($posY >= $this->getLength());
    }

    public function __toString()
    {
        $printedRows = array($this->getCode());
        for ($x = 0; $x < $this->getLength(); $x++) {
            $printedRows[] = $this->printRow($x);
        }

        return implode("/", $printedRows);
    }

    protected function printRow($x)
    {
        $row = "";
        for ($y = 0; $y < $this->getLength(); $y++) {
            $row .= $this->getBlock($x, $y)->__toString();
        }

        return $row;
    }

    public function getCode()
    {
        return substr($this->hash, strlen($this->hash) - 3, 3);
    }

}