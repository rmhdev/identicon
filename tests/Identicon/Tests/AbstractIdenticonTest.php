<?php

namespace Identicon;

use Identicon\Identity\Identity;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

abstract class AbstractIdenticonTest extends \PHPUnit_Framework_TestCase
{

    public function testGetIdentity()
    {
        $identicon = $this->createIdenticon("myidentity");
        $identity = $identicon->getIdentity();
        $expectedIdentity = new Identity("myidentity");
        $this->assertEquals($identity->__toString(), $expectedIdentity->__toString());
    }

    public function testGetContentGeneratesImage()
    {
        $identicon = $this->createIdenticon("myidentity");
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
        $identicon = $this->createIdenticon("probando");
        $filename = $this->createFile($identicon);
        $image = $this->createImage($filename);

        $length = $identicon->getIdentity()->getLength();
        for ($x = 0; $x < $length; $x++) {
            for ($y = 0; $y < $length; $y++) {
                $backgroundColor = (string) $identicon->getBackgroundColor();
                list($centerX, $centerY) = $this->calculateCenter($x, $y);
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

    protected function calculateCenter($x, $y)
    {
        $calc = AbstractIdenticon::MARGIN + AbstractIdenticon::BLOCK_SIZE/2;

        return array(
            $calc + $x*AbstractIdenticon::BLOCK_SIZE,
            $calc + $y*AbstractIdenticon::BLOCK_SIZE
        );
    }

    protected function createFile(AbstractIdenticon $identicon)
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
        $identicon = $this->createIdenticon("myidentity");
        $this->assertInstanceOf("\Imagine\Image\Color", $identicon->getColor());
    }

    public function testGetBackgroundColor()
    {
        $identicon = $this->createIdenticon("myidentity");
        $backgroundColor = $identicon->getBackgroundColor();
        $this->assertInstanceOf("\Imagine\Image\Color", $backgroundColor);
        $this->assertStringEndsWith(AbstractIdenticon::BACKGROUND_COLOR, (string) $backgroundColor);
    }

    public function testPersonalizedBackgroundColor()
    {
        $identicon = $this->createIdenticon("myidentity", array("background-color" => "5f0963"));
        $backgroundColor = $identicon->getBackgroundColor();
        $this->assertEquals("#5f0963", (string) $backgroundColor);

        $filename = $this->createFile($identicon);
        $image = $this->createImage($filename);
        $actualBackgroundColor = $image->getColorAt(new Point(0, 0));
        $this->assertEquals((string) $backgroundColor, (string) $actualBackgroundColor);
        unlink($filename);
    }

    /**
     * @expectedException \Identicon\Exception\InvalidArgumentException
     */
    public function testIncorrectBackgroundColorThrowsException()
    {
        $identicon = $this->createIdenticon("myidentity", array("background-color" => "55"));
    }

    public function testPersonalizedMargin()
    {
        $identicon = $this->createIdenticon("myidentity", array("margin" => 10));
        $expectedWidth = 10*2 + AbstractIdenticon::BLOCKS*AbstractIdenticon::BLOCK_SIZE;
        $this->assertEquals($expectedWidth, $identicon->getWidth());

        $expectedHeight = $expectedWidth;
        $this->assertEquals($expectedHeight, $identicon->getHeight());
    }

    /**
     * @dataProvider incorrectMarginValuesProvider
     * @expectedException \Identicon\Exception\InvalidArgumentException
     */
    public function testIncorrectMarginThrowException($marginValue)
    {
        $this->createIdenticon("myidentity", array("margin" => $marginValue));
    }

    public function incorrectMarginValuesProvider()
    {
        return array(
            array(-5),
            array("test"),
            array("")
        );
    }

    public function testPersonalizedBlockSize()
    {
        $identicon = $this->createIdenticon("myIdentity", array("block-size" => 50));
        $expectedWidth = AbstractIdenticon::MARGIN*2 + AbstractIdenticon::BLOCKS*50;
        $this->assertEquals($expectedWidth, $identicon->getWidth());

        $expectedHeight = $expectedWidth;
        $this->assertEquals($expectedHeight, $identicon->getHeight());
    }

    /**
     * @dataProvider incorrectBlockSizesValuesProvider
     * @expectedException \Identicon\Exception\InvalidArgumentException
     */
    public function testIncorrectBlockSizeThrowException($blockSize)
    {
        $this->createIdenticon("myidentity", array("block-size" => $blockSize));
    }

    public function incorrectBlockSizesValuesProvider()
    {
        return array(
            array(-5),
            array("test")
        );
    }

    public function testCustomizedBlockNumber()
    {
        $identicon = $this->createIdenticon("myIdentity", array("blocks" => 7));
        $expectedWidth = AbstractIdenticon::MARGIN*2 + 7*AbstractIdenticon::BLOCK_SIZE;
        $this->assertEquals($expectedWidth, $identicon->getWidth());
    }

    /**
     * @param $name
     * @param array $options
     * @return AbstractIdenticon
     */
    abstract protected function createIdenticon($name, $options = array());

}