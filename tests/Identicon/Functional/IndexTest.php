<?php

namespace Identicon\Tests;

use Silex\WebTestCase;

class IndexTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../../../src/production.php";
    }

    public function testLoadingIndexPage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('html:contains("Identicon")')->count());
    }
}