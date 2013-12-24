<?php

namespace Identicon\Tests;

class ExtraTypeIdenticonTest extends AbstractTypeIdenticonTest
{
    public function testTrianglePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/triangle.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-triangle.png");
    }

    public function testCirclePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/circle.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-circle.png");
    }

    public function testStarPage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/star.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertImageIsCorrect($response, "identity-star.png");
    }


    public function testUnknowTypePage()
    {
        $client = $this->createClient();
        $client->request("GET", "/identity/unknown.png");
        $response = $client->getResponse();

        $this->assertTrue($response->isClientError());
    }
}