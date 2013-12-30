<?php

namespace Identicon\Tests\Type;

use Identicon\AbstractIdenticonTest;
use Identicon\Type\Circle\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}