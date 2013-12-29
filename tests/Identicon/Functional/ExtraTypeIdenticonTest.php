<?php

namespace Identicon\Tests;

class ExtraTypeIdenticonTest extends AbstractTypeIdenticonTest
{
    public function testTrianglePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/pyramid.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-pyramid.png");
    }

    public function testCirclePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/circle.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-circle.png");
    }

    public function testRhombusPage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/rhombus.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-rhombus.png");
    }


    public function testUnknownTypePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/unknown.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isClientError());
    }

    public function testCachedExtraType()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/circle.png");
        $response = $client->getResponse();
        $this->assertTrue($response->isCacheable());
        $this->assertEquals(3600, $response->getMaxAge());
        $this->assertTrue($response->isValidateable());
    }
}