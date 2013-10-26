<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

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

    public function testGetContentGeneratesImage()
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
        unlink($filename);
    }

    public function testGetContentDrawsIdentityInImage()
    {
        $identicon = new Identicon("myidentity");
        $filename = sprintf("%s/%s.%s", sys_get_temp_dir(), uniqid("identicon-test-"), "png");
        file_put_contents($filename, $identicon->getContent());
        $imagine = new Imagine();
        $image = $imagine->open($filename);

        $isColored = $identicon->getIdentity()->getBlock(0, 0)->isColored();
        $expectedHexColor = $isColored ? "#555555" : "#ffffff";
        $color = $image->getColorAt(new Point(65, 65));
        $this->assertEquals($expectedHexColor, (string) $color);

        $isColored = $identicon->getIdentity()->getBlock(0, 1)->isColored();
        $expectedHexColor = $isColored ? "#555555" : "#ffffff";
        $color = $image->getColorAt(new Point(100, 65));
        $this->assertEquals($expectedHexColor, (string) $color);

        $isColored = $identicon->getIdentity()->getBlock(0, 2)->isColored();
        $expectedHexColor = $isColored ? "#555555" : "#ffffff";
        $color = $image->getColorAt(new Point(135, 65));
        $this->assertEquals($expectedHexColor, (string) $color);



    }

}