<?php

namespace Identicon\Tests\Types;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Circle\Identicon;

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