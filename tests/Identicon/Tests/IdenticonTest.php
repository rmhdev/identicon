<?php

namespace Identicon;

use Identicon\Identity\Identity;

class IdenticonTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateNew()
    {
        $identicon = new Identicon("myidentity");

        $this->assertInstanceOf("\Identicon\Identicon", $identicon);
    }

    public function testGetIdentity()
    {
        $identicon = new Identicon("myidentity");
        $identity = $identicon->getIdentity();
        $expectedIdentity = new Identity("myidentity");
        $this->assertEquals($identity->__toString(), $expectedIdentity->__toString());
    }
}