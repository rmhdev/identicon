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
}