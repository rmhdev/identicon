<?php

namespace Identicon\Tests\Types\Square;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Square\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}