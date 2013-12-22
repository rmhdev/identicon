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

    public function testHtmlTitleContainsTheName()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");

        $this->assertContains("Identicons", $crawler->filter("html > head > title")->text());
        $this->assertContains("identicons", $crawler->filter('html > head > meta[name="description"]')->attr("content"));
    }

    public function testHeaderLink()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");

        $this->assertGreaterThanOrEqual(1, $crawler->filter('a.navbar-brand')->count());

        $host = $client->getServerParameter("HTTP_HOST");
        $this->assertEquals("http://{$host}/", $crawler->filter('a.navbar-brand ')->attr("href"));
    }

    public function testGenerateEmptyNameReturnsError()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $form = $crawler->selectButton('generate')->form();

        // set some values
        $form['name'] = '';

        // submit the form
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter("form.error")->count());
    }

    public function testGenerateCorrectNameRedirectToProfile()
    {
        $client = $this->createClient();
        $crawler = $client->request("GET", "/");
        $form = $crawler->selectButton('generate')->form();

        // set some values
        $form['name'] = 'mytest';

        // submit the form
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
    }
}