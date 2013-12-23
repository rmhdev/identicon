<?php

namespace Identicon\Tests;

use Imagine\Gd\Imagine;
use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BasicIdenticonTest extends WebTestCase
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

    public function testLoadingIndexPage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/basic/identity.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals("image/png", $response->headers->get("Content-Type"));

        $filename = $this->createFileFromResponse($response, "identity.png");
        $this->assertFileExists($filename);
        $this->assertEquals("image/png", $this->retrieveMimeType($filename));

        $imagine = new Imagine();
        $image = $imagine->open($filename);
        $this->assertEquals(420, $image->getSize()->getWidth());
        $this->assertEquals(420, $image->getSize()->getHeight());
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

    public function testCachedBasicIdenticonPage()
    {
        $client = $this->createClient();
        $client->request("GET", "/basic/identity.png");
        $response = $client->getResponse();
        $this->assertTrue($response->isCacheable());
        $this->assertEquals(3600, $response->getMaxAge());
        $this->assertTrue($response->isValidateable());
        //$requestA = $client->getRequest();
        //$this->assertFalse($response->isNotModified($requestA));
    }
}