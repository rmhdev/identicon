<?php

namespace Identicon\Tests;

use Silex\WebTestCase;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTypeIdenticonTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../../../src/production.php";
    }

    public function setUp()
    {
        parent::setUp();
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
        $this->assertEquals(420, $image->getSize()->getWidth());
        $this->assertEquals(420, $image->getSize()->getHeight());
    }
}
