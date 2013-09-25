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

        $block00 = $identity->getBlock(0, 0);
        $this->assertInstanceOf("Identicon\Identity\Block", $block00);

        $block22 = $identity->getBlock(2, 2);
        $this->assertInstanceOf("Identicon\Identity\Block", $block22);

        $block44 = $identity->getBlock(4, 4);
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
        $identity->getBlock($posX, $posY);
    }

    public function testIdentitiesFromDifferentInputs()
    {
        $identity1 = new Identity("myidentity");
        $identity2 = new Identity("youridentity");
        $equalIdentities = $identity1->__toString() ===  $identity2->__toString();

        $this->assertFalse($equalIdentities);
    }

    public function symmetricOutputProvider()
    {
        return array(
            array(0, 0, 0, 4),
            array(0, 1, 0, 3),
            array(1, 0, 1, 4),
            array(1, 1, 1, 3),
            array(2, 0, 2, 4),
            array(2, 1, 2, 3),
            array(3, 0, 3, 4),
            array(3, 1, 3, 3),
            array(4, 0, 4, 4),
            array(4, 1, 4, 3),
        );
    }

    /**
     * @dataProvider symmetricOutputProvider
     */
    public function testSymmetricOutput($leftX, $leftY, $rightX, $rightY)
    {
        $identity = new Identity("myidentity");

        $blockLeft = $identity->getBlock($leftX, $leftY);
        $blockRight = $identity->getBlock($rightX, $rightY);
        $this->assertEquals($blockLeft->isColored(), $blockRight->isColored());
    }

}
