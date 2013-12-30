<?php

namespace Identicon\Tests\Type\Pyramid;

use Identicon\AbstractIdenticonTest;
use Identicon\Type\Pyramid\Identicon;

class IdenticonTest extends AbstractIdenticonTest
{
    protected function createIdenticon($name, $options = array())
    {
        return new Identicon($name, $options);
    }
}