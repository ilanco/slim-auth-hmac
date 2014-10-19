<?php

namespace Test\IC\SlimAuthHmac\Middleware\Auth;

use Slim\Middleware\ContentTypes;
use IC\SlimAuthHmac\Middleware\Auth\Hmac;
use IC\SlimAuthHmac\Auth\HmacManager;

class HmacTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected $privateKey;

    protected $hmacManager;

    public function setup()
    {
        $this->privateKey = sha1(uniqid());

        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));

        $this->hmacManager = new HmacManager();
        $this->hmacManager->setPrivateKey($this->privateKey);
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

        // $this->app->add(new ContentTypes());
        $this->app->add(new Hmac(array(
            'privateKey' => $this->privateKey
        )));

        $requestMethod = 'POST';
        $requestResourceUri = '/';
        $requestBody = json_encode(array(
            'test' => true
        ));
        $payload = '';
        $payload .= $requestMethod . "\n";
        $payload .= $requestResourceUri  . "\n";
        $payload .= $requestBody;
        $this->hmacManager->setPayload($payload);

        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $requestMethod,
            'PATH_INFO' => $requestResourceUri,
            'SERVER_NAME' => 'local.dev',
            'HTTP_CONTENT-TYPE' => 'application/json',
            'HTTP_AUTHENTICATION' => 'hmac testapikey:' . $this->hmacManager->generateHmac(),
            'slim.input' => $requestBody
        ));

        $this->app->post('/', function () {
        });

        $this->app->run();

        $output = ob_get_clean();

        $this->assertEquals(200, $this->app->response()->getStatus());
    }
}
