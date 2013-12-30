<?php

namespace Identicon\Tests\Type\Square;

use Identicon\AbstractIdenticonTest;
use Identicon\Type\Square\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}