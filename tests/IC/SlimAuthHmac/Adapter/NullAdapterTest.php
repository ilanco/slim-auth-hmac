<?php

namespace Test\IC\SlimAuthHmac\Adapter;

use IC\SlimAuthHmac\Adapter;

class NullAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $adapter = new Adapter\NullAdapter();

        $this->assertInstanceOf('IC\\SlimAuthHmac\\Adapter\\AbstractAdapter', $adapter);
    }

    public function testAuthenticate()
    {
        $adapter = new Adapter\NullAdapter();

        $result = $adapter->authenticate();

        $this->assertNull($result);
    }
}
