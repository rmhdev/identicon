<?php

namespace Identicon\Tests\Cell;

use Identicon\Cell\Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPositionX()
    {
        $cellA = new Cell(0, 0);
        $this->assertEquals(0, $cellA->getPositionX());

        $cellB = new Cell(1, 0);
        $this->assertEquals(1, $cellB->getPositionX());
    }

    public function testGetPositionY()
    {
        $cellA = new Cell(0, 0);
        $this->assertEquals(0, $cellA->getPositionY());

        $cellB = new Cell(0, 2);
        $this->assertEquals(2, $cellB->getPositionY());
    }

    public function testGetWidth()
    {
        $cellA = new Cell(0, 0);
        $this->assertEquals(10, $cellA->getWidth());

        $cellB = new Cell(1, 1, array("width" => 15));
        $this->assertEquals(15, $cellB->getWidth());
    }

    public function testGetHeight()
    {
        $cellA = new Cell(0, 0);
        $this->assertEquals(10, $cellA->getHeight());

        $cellB = new Cell(0, 0, array("height" => 20));
        $this->assertEquals(20, $cellB->getHeight());

        $cellC = new Cell(0, 0, array("width" => 20));
        $this->assertEquals(20, $cellC->getHeight());
    }

    public function testGetNorth()
    {
        $cellA = new Cell(0, 0);
        $pointA = $cellA->getNorth();
        $this->assertEquals(5, $pointA->getX());
        $this->assertEquals(0, $pointA->getY());

        $cellB = new Cell(0, 0, array("width" => 20));
        $pointB = $cellB->getNorth();
        $this->assertEquals(10, $pointB->getX());
        $this->assertEquals(0, $pointB->getY());

        $cellC = new Cell(2, 2, array("width" => 10));
        $pointC = $cellC->getNorth();
        $this->assertEquals((2 * 10) + 5, $pointC->getX());
        $this->assertEquals((2 * 10) + 0, $pointC->getY());
    }

    public function testGetSouth()
    {
        $cellA = new Cell(0, 0);
        $pointA = $cellA->getSouth();
        $this->assertEquals(5, $pointA->getX());
        $this->assertEquals(10, $pointA->getY());

        $cellB = new Cell(0, 0, array("width" => 20));
        $pointB = $cellB->getSouth();
        $this->assertEquals(10, $pointB->getX());
        $this->assertEquals(20, $pointB->getY());

        $cellC = new Cell(2, 2, array("width" => 30, "height" => 50));
        $pointC = $cellC->getSouth();
        $this->assertEquals((2 * 30) + 15, $pointC->getX());
        $this->assertEquals((2 * 50) + 50, $pointC->getY());
    }

//    public function testGetEast()
//    {
//        $cellA = new Cell(0, 0);
//        $pointA = $cellA->getEast();
//        $this->assertEquals(10, $pointA->getX());
//        $this->assertEquals(5, $pointA->getY());
//
//        $cellB = new Cell(0, 0, array("width" => 20));
//        $pointB = $cellB->getSouth();
//        $this->assertEquals(20, $pointB->getX());
//        $this->assertEquals(10, $pointB->getY());
//    }
}

