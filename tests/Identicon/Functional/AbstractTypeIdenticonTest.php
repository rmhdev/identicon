<?php

namespace Identicon\Tests;

use Identicon\AbstractIdenticon;
use Silex\WebTestCase;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTypeIdenticonTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../config/test.php";
    }

    public function setUp()
    {
        parent::setUp();
        if (file_exists($this->getTempDir())) {
            rmdir($this->getTempDir());
        }
        mkdir($this->getTempDir(), 0777, true);
    }

    public function tearDown()
    {
        foreach (glob($this->getTempDir() . "/*") as $filename) {
            unlink($filename);
        }
        rmdir($this->getTempDir());
        parent::tearDown();
    }

    protected function getTempDir()
    {
        return sys_get_temp_dir() . "/identicon-functional";
    }

    protected function createFileFromResponse(Response $response, $name = "file.png")
    {
        $filename = sprintf("%s/%s", $this->getTempDir(), $name);
        file_put_contents($filename, $response->getContent());

        return $filename;
    }

    protected function retrieveMimeType($filename)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
    }

    protected function createImagine()
    {
        return new Imagine();
    }

    protected function assertImageIsCorrect(Response $response, $imageName = "file.png")
    {
        $this->assertEquals("image/png", $response->headers->get("Content-Type"));

        $filename = $this->createFileFromResponse($response, $imageName);
        $this->assertFileExists($filename);
        $this->assertEquals("image/png", $this->retrieveMimeType($filename));

        $imagine = $this->createImagine();
        $image = $imagine->open($filename);
        $widthHeight = AbstractIdenticon::MARGIN*2 + AbstractIdenticon::BLOCKS*AbstractIdenticon::BLOCK_SIZE;
        $this->assertEquals($widthHeight, $image->getSize()->getWidth());
        $this->assertEquals($widthHeight, $image->getSize()->getHeight());
    }
}
