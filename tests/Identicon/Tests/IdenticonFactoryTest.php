<?php

namespace Identicon\Tests;

use Identicon\Exception\InvalidArgumentException;
use Identicon\IdenticonFactory;
use Identicon\Type\Plain\Identicon as DefaultIdenticon;

class IdenticonFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDefaultShouldReturnPlainIdenticon()
    {
        $options = array(
            "blocks" => 5,
            "block-size" => 70,
            "margin" => 35,
            "background-color" => "f0f0f0"
        );
        $identicon = new DefaultIdenticon("factory-plain", $options);

        $this->assertEquals(
            $identicon->getContent(),
            IdenticonFactory::createDefault("factory-plain", $options)->getContent()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateWithEmptyTypeShouldThrowException()
    {
        IdenticonFactory::create("", "factory-plain", array());
    }

    public function testCreateTypeShouldReturnIdenticon()
    {
        $options = array(
            "blocks" => 5,
            "block-size" => 70,
            "margin" => 35,
            "background-color" => "f0f0f0"
        );

        $this->assertInstanceOf(
            'Identicon\Type\Pyramid\Identicon',
            IdenticonFactory::create("pyramid", "factory-plain", $options)
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateUnknownTypeShouldThrowException()
    {
        IdenticonFactory::create("unknown", "factory-plain", array());
    }
}
