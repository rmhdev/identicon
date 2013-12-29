<?php

namespace Identicon\Tests\Types\Rhombus;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Rhombus\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}