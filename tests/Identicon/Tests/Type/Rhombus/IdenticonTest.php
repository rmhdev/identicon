<?php

namespace Identicon\Tests\Type\Rhombus;

use Identicon\AbstractIdenticonTest;
use Identicon\Type\Rhombus\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}