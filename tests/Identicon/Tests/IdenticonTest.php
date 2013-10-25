<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;

class IdenticonTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateNew()
    {
        $identicon = new Identicon("myidentity");

        $this->assertInstanceOf("\Identicon\Identicon", $identicon);
    }

    public function testGetIdentity()
    {
        $identicon = new Identicon("myidentity");
        $identity = $identicon->getIdentity();
        $expectedIdentity = new Identity("myidentity");
        $this->assertEquals($identity->__toString(), $expectedIdentity->__toString());
    }

    public function testGetContentDefaultSize()
    {
        $identicon = new Identicon("myidentity");
        $filename = sprintf("%s/%s.%s", sys_get_temp_dir(), uniqid("identicon-test-"), "png");
        file_put_contents($filename, $identicon->getContent());
        $this->assertFileExists($filename);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        $this->assertEquals("image/png", $mimeType);

        $imagine = new Imagine();
        $image = $imagine->open($filename);
        $this->assertEquals(420, $image->getSize()->getWidth());
        $this->assertEquals(420, $image->getSize()->getHeight());
    }
}