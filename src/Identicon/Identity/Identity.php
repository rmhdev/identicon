<?php

namespace Identicon\Identity;

use Identicon\Exception\OutOfBoundsException;

class Identity
{

    protected
        $name,
        $hash,
        $blocks;

    public function __construct($name)
    {
        $this->prepareIdentification($name);
        $this->initializeBlocks();
    }

    protected function prepareIdentification($identification)
    {
        $this->name = mb_convert_case($identification, MB_CASE_LOWER, "UTF-8");
        $this->hash = sha1($this->name);
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
        if (!isset($this->blocks[$y])) {
            $this->blocks[$y] = array();
        }
        $this->blocks[$y][$x] = $this->createBlock($x, $y);
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
        if ($posX > $this->getLength() / 2) {
            $posX = $this->getLength() - $posX - 1;
        }

        return $posY * $this->getLength() + $posX;
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

        return $this->blocks[$posY][$posX];
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

    public function getName()
    {
        return $this->name;
    }

}