<?php

namespace Identicon\Tests\Type\Plain;

use Identicon\AbstractIdenticonTest;
use Identicon\Type\Plain\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}
