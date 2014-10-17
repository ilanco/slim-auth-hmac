<?php

namespace Test\IC\SlimAuthHmac\Auth;

use IC\SlimAuthHmac\Auth\HmacManager;

class HmacManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testConstruct()
    {
        $hmacManager = new HmacManager();

        $this->assertInstanceOf('IC\\SlimAuthHmac\\Auth\\HmacManager', $hmacManager);
    }
}
