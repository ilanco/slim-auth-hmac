<?php

namespace Test\IC\Slim\Auth;

use IC\Slim\Auth\HmacManager;

class HmacManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testConstruct()
    {
        $hmacManager = new HmacManager();

        $this->assertInstanceOf('IC\\Slim\\Auth\\HmacManager', $hmacManager);
    }
}
