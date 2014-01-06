<?php

namespace Identicon\Tests;

class BasicTypeIdenticonTest extends AbstractTypeIdenticonTest
{
    public function testLoadingIndexPage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity.png");
    }

    public function testCachedBasicIdenticonPage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity.png");
        $response = $client->getResponse();
        $this->assertTrue($response->isCacheable());
        $this->assertEquals(3600, $response->getMaxAge());
        $this->assertTrue($response->isValidateable());
        //$requestA = $client->getRequest();
        //$this->assertFalse($response->isNotModified($requestA));
    }

    public function testIncorrectFormat()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity.jpg");
        $response = $client->getResponse();

        $this->assertTrue($response->isClientError());
    }

    public function testNonLowercaseFormat()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity.PNG");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }
}