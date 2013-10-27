<?php

namespace Identicon\Tests;

use Silex\WebTestCase;

class ProfileTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../../../src/production.php";
    }

    public function testLoadingProfilePage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/myidentity");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertGreaterThanOrEqual(1, $crawler->filter('html:contains("myidentity")')->count());
    }
}