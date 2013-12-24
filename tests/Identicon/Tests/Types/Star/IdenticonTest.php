<?php

namespace Identicon\Tests\Types\Star;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Star\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}