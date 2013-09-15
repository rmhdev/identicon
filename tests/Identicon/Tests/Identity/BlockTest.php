<?php

namespace Identicon\Tests\Identity;

use Identicon\Identity\Block;
use Imagine\Image\Color;

class BlockTest extends \PHPUnit_Framework_TestCase
{
    public function testBlockIsColored()
    {
        $blockNotColored = new Block(false);
        $this->assertEquals(false, $blockNotColored->isColored());

        $blockColored = new Block(true);
        $this->assertEquals(true, $blockColored->isColored());
    }
}