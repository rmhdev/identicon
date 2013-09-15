<?php

namespace Identicon\Tests\Identity;

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
        $block = $identity->getPosition(0, 0);

        $this->assertInstanceOf("Identicon\Identity\Block", $block);
    }
}
