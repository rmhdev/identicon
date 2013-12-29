<?php

namespace Identicon\Tests\Types\Pyramid;

use Identicon\AbstractIdenticonTest;
use Identicon\Types\Pyramid\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}