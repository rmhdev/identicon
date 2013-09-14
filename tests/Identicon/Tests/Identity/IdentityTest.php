<?php

namespace Identicon\Tests\Identity;

use Identicon\Identity\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testIdentityLength()
    {
        $identity = new Identity();

        $this->assertEquals(5, $identity->getLength());
    }
}
