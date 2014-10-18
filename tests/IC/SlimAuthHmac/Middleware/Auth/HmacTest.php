<?php

namespace Test\IC\SlimAuthHmac\Middleware\Auth;

use IC\SlimAuthHmac\Middleware\Auth\Hmac;

class HmacTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setup()
    {
        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));
    }

    public function testConstruct()
    {
        $hmac = new Hmac();
        $hmac->setApplication($this->app);

        $this->assertInstanceOf('IC\\SlimAuthHmac\\Middleware\\Auth\\Hmac', $hmac);
    }

    public function testMiddleware()
    {
        ob_start();

        $this->app->add(new Hmac());

        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => 'POST',
            'PATH_INFO' => '',
            'SERVER_NAME' => 'local.dev',
            'HTTP_AUTHENTICATION' => 'hmac 1:1'
        ));

        $this->app->run();

        ob_get_clean();

        $this->assertEquals(200, $this->app->response()->getStatus());
    }
}
