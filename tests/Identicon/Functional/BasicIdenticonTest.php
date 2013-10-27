<?php

namespace Identicon\Tests;

use Imagine\Gd\Imagine;
use Silex\WebTestCase;

class BasicIdenticonTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../../../src/production.php";
    }

    public function testLoadingIndexPage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/basic/identity.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals("image/png", $response->headers->get("Content-Type"));

        $tempDir = sys_get_temp_dir();
        $filename = $tempDir . "/" . "identity.png";
        file_put_contents($filename, $response->getContent());
        $this->assertFileExists($filename);

        $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        $this->assertEquals("image/png", $mimeType);

        $imagine = new Imagine();
        $image = $imagine->open($filename);
        $this->assertEquals(420, $image->getSize()->getWidth());
        $this->assertEquals(420, $image->getSize()->getHeight());
    }
}