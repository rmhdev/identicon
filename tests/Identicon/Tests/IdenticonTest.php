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
        $filename = $this->createFile($identicon);
        $this->assertFileExists($filename);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        $this->assertEquals("image/png", $mimeType);

        $image = $this->createImage($filename);
        $this->assertEquals(420, $image->getSize()->getWidth());
        $this->assertEquals(420, $image->getSize()->getHeight());
        unlink($filename);
    }

    public function testGetContentDrawsIdentityInImage()
    {
        $identicon = new Identicon("myidentity");
        $filename = $this->createFile($identicon);
        $image = $this->createImage($filename);

        $length = $identicon->getIdentity()->getLength();
        for ($x = 0; $x < $length; $x++) {
            for ($y = 0; $y < $length; $y++) {
                $backgroundColor = '#' . Identicon::BACKGROUND_COLOR;
                $centerX = Identicon::MARGIN + $y*Identicon::BLOCK_SIZE + Identicon::BLOCK_SIZE/2;
                $centerY = Identicon::MARGIN + $x*Identicon::BLOCK_SIZE + Identicon::BLOCK_SIZE/2;
                $color = $image->getColorAt(new Point($centerX, $centerY));
                $comment = "position [{$x}, {$y}]: {$centerX}px, {$centerY}px";
                if ($identicon->getIdentity()->getBlock($x, $y)->isColored()) {
                    $this->assertNotEquals($backgroundColor, (string) $color, $comment);
                } else {
                    $this->assertEquals($backgroundColor, (string) $color, $comment);
                }
            }
        }
        unlink($filename);
    }

    protected function createFile(Identicon $identicon)
    {
        $filename = sprintf("%s/%s.%s", sys_get_temp_dir(), uniqid("identicon-test-"), "png");
        file_put_contents($filename, $identicon->getContent());

        return $filename;
    }

    protected function createImage($filename)
    {
        $imagine = new Imagine();
        return $imagine->open($filename);
    }

    public function testGetColor()
    {
        $identicon = new Identicon("myidentity");
        $this->assertInstanceOf("\Imagine\Image\Color", $identicon->getColor());
    }

    public function testGetBackgroundColor()
    {
        $identicon = new Identicon("myidentity");
        $backgroundColor = $identicon->getBackgroundColor();
        $this->assertInstanceOf("\Imagine\Image\Color", $backgroundColor);
        $this->assertStringEndsWith(Identicon::BACKGROUND_COLOR, (string) $backgroundColor);
    }

}