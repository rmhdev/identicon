<?php

namespace Identicon\Tests\Types\Triangle;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Triangle\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function setUp()
    {
        $this->markTestSkipped("not now...");
    }

    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}