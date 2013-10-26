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
                $backgroundColor = (string) $identicon->getBackgroundColor();
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
        //unlink($filename);
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

    public function testPersonalizedBackgroundColor()
    {
        $identicon = new Identicon("myidentity", array("background-color" => "5f0963"));
        $backgroundColor = $identicon->getBackgroundColor();
        $this->assertEquals("#5f0963", (string) $backgroundColor);

        $filename = $this->createFile($identicon);
        $image = $this->createImage($filename);
        $actualBackgroundColor = $image->getColorAt(new Point(0, 0));
        $this->assertEquals((string) $backgroundColor, (string) $actualBackgroundColor);
        unlink($filename);
    }

    public function testPersonalizedMargin()
    {
        $identicon = new Identicon("myidentity", array("margin" => 10));
        $expectedWidth = 10*2 + $identicon->getIdentity()->getLength()*Identicon::BLOCK_SIZE;
        $this->assertEquals($expectedWidth, $identicon->getWidth());

        $expectedHeight = $expectedWidth;
        $this->assertEquals($expectedHeight, $identicon->getHeight());
    }

    public function testPersonalizedBLockSize()
    {
        $identicon = new Identicon("myIdentity", array("block-size" => 50));
        $expectedWidth = Identicon::MARGIN*2  + $identicon->getIdentity()->getLength() * 50;
        $this->assertEquals($expectedWidth, $identicon->getWidth());
    }

}