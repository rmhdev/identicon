<?php

namespace Identicon\Identity;

use Identicon\Exception\OutOfBoundsException;
use Identicon\Exception\InvalidArgumentException;

final class Identity
{
    const MAX_LENGTH = 255;

    private $name;
    private $options;
    private $hash;
    private $blocks;

    public function __construct($name, $options = array())
    {
        $this->options = $this->prepareOptions($options);
        $this->initializeIdentification($name);
        $this->initializeBlocks();
    }

    private function prepareOptions($options = array())
    {
        if (!isset($options["length"])) {
            $options["length"] = 5;
        }
        $options["length"] = $this->processLength($options["length"]);

        return $options;
    }

    private function processLength($length)
    {
        if (!is_numeric($length)) {
            throw new InvalidArgumentException();
        }
        if ($length <= 0) {
            throw new OutOfBoundsException();
        }

        return (int) $length;
    }

    private function initializeIdentification($identification)
    {
        $name = mb_convert_case($identification, MB_CASE_LOWER, "UTF-8");
        if (self::MAX_LENGTH < mb_strlen($name)) {
            throw new OutOfBoundsException(
                sprintf('Identity name is too long (%d), max is %d', mb_strlen($name), self::MAX_LENGTH)
            );
        }
        $this->name = $name;
        $this->hash = sha1($this->name);
    }

    private function initializeBlocks()
    {
        $this->blocks = array();
        for ($x = 0; $x < $this->getLength(); $x++) {
            for ($y = 0; $y < $this->getLength(); $y++) {
                $this->insertBlock($x, $y);
            }
        }
    }

    private function insertBlock($x, $y)
    {
        if (!isset($this->blocks[$y])) {
            $this->blocks[$y] = array();
        }
        $this->blocks[$y][$x] = $this->createBlock($x, $y);
    }

    private function createBlock($posX, $posY)
    {
        return new Block($this->isPositionColored($posX, $posY));
    }

    private function isPositionColored($posX, $posY)
    {
        // value is hexadecimal number: [0..7] [8..15]
        return $this->calculateValueForPosition($posX, $posY) >= 8 ? true : false;
    }

    private function calculateValueForPosition($posX, $posY)
    {
        return hexdec(substr($this->hash, $this->calculateCharPosition($posX, $posY), 1));
    }

    private function calculateCharPosition($posX, $posY)
    {
        if ($posX >= $this->getLength() / 2) {
            $posX = $this->getLength() - $posX - 1;
        }
        $position = $posY * ceil($this->getLength() / 2) + $posX;

        return $position % strlen($this->hash);
    }

    public function getLength()
    {
        return $this->getOption("length");
    }

    private function getOption($name, $default = null)
    {
        if (!isset($this->options[$name])) {
            return $default;
        }

        return $this->options[$name];
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

    private function isOutOfBounds($posX, $posY)
    {
        return $posX < 0 || $posY < 0 || ($posX >= $this->getLength()) || ($posY >= $this->getLength());
    }

    public function __toString()
    {
        $printedRows = array($this->getCode());
        for ($row = 0; $row < $this->getLength(); $row++) {
            $printedRows[] = $this->printRow($row);
        }

        return implode("/", $printedRows);
    }

    private function printRow($x)
    {
        $row = "";
        for ($col = 0; $col < $this->getLength(); $col++) {
            $row .= $this->getBlock($x, $col)->__toString();
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
