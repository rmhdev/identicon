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
        $this->assertEquals(1, $crawler->filter('.container img')->count());
    }

    public function testHtmlTitleContainsTheName()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/myidentity");

        $this->assertStringStartsWith("myidentity", $crawler->filter("html > head > title")->text());
    }
}