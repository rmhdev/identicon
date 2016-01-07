<?php

namespace Identicon\Tests;

use Silex\WebTestCase;

class ProfileTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__ . "/../config/test.php";
    }

    public function testLoadingProfilePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/myidentity");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider identityNamesProvider
     */
    public function testProfileContainsName($name, $expected)
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/{$name}");

        $this->assertContains($expected, $crawler->filter('html > body > header')->text());
    }

    public function identityNamesProvider()
    {
        return array(
            array("myidentity", "myidentity"),
            array("MyIdentity", "myidentity"),
            array("IdËntificaÇióñ", "idëntificaçióñ"),
        );
    }

    /**
     * @dataProvider identityNamesProvider
     */
    public function testHtmlHeadContainsName($name, $expected)
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/{$name}");

        $this->assertContains($expected, $crawler->filter("html > head > title")->text());
        $this->assertContains($expected, $crawler->filter('html > head > meta[name="description"]')->attr("content"));
    }

    public function testProfileContainsImage()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/myidentity");

        $this->assertEquals(1, $crawler->filter('.container .identicon-image-basic img')->count());
    }

    public function testCachedProfilePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/myidentity");
        $response = $client->getResponse();
        $this->assertTrue($response->isCacheable());
        $this->assertEquals(3600, $response->getMaxAge());
        $this->assertTrue($response->isValidateable());
    }

    public function testProfileContainsExtraImages()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/myidentity");
        $extraTypes = $this->app["identicon.type"]["extra"];

        $this->assertEquals(
            sizeof($extraTypes),
            $crawler->filter('.container .identicon-extras a')->count()
        );
    }

}
