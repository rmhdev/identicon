<?php

namespace Identicon\Tests\Identity;

use Identicon\Exception\OutOfBoundsException;
use Identicon\Identity\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testIdentityLength()
    {
        $identity = new Identity("name");

        $this->assertEquals(5, $identity->getLength());
    }

    public function testIdentityGetPosition()
    {
        $identity = new Identity("name");

        $block00 = $identity->getPosition(0, 0);
        $this->assertInstanceOf("Identicon\Identity\Block", $block00);

        $block22 = $identity->getPosition(2, 2);
        $this->assertInstanceOf("Identicon\Identity\Block", $block22);

        $block44 = $identity->getPosition(4, 4);
        $this->assertInstanceOf("Identicon\Identity\Block", $block44);
    }

    public function outOfBoundsPositionProvider()
    {
        return array(
            array(-1, 0),
            array(0, -1),
            array(5, 0),
            array(0, 5),
        );
    }

    /**
     * @dataProvider outOfBoundsPositionProvider
     * @expectedException \Identicon\Exception\OutOfBoundsException
     */
    public function testIdentityGetOutOfBoundsPosition($posX, $posY)
    {
        $identity = new Identity("name");
        $identity->getPosition($posX, $posY);
    }

}
