<?php

namespace Test\IC\SlimAuthHmac\Middleware\Auth;

use Slim\Middleware\ContentTypes;
use IC\SlimAuthHmac\Middleware\Auth\Hmac;
use IC\SlimAuthHmac\Auth\HmacManager;
use IC\SlimAuthHmac\Adapter\NullAdapter;

class HmacTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    protected $privateKey;

    protected $hmacManager;

    public function setup()
    {
        $this->privateKey = sha1(uniqid());

        $this->hmacManager = new HmacManager();
        $this->hmacManager->setPrivateKey($this->privateKey);
    }

    public function testConstruct()
    {
        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));

        $hmac = new Hmac($this->hmacManager, new NullAdapter());
        $hmac->setApplication($this->app);

        $this->assertInstanceOf('IC\\SlimAuthHmac\\Middleware\\Auth\\Hmac', $hmac);
    }

    public function testAllowedRoute()
    {
        $requestMethod = 'POST';
        $requestResourceUri = '/';
        $requestBody = json_encode(array(
            'test' => true
        ));

        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $requestMethod,
            'PATH_INFO' => $requestResourceUri,
            'SERVER_NAME' => 'local.dev',
            'HTTP_CONTENT-TYPE' => 'application/json',
            'slim.input' => $requestBody
        ));

        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));

        $this->app->add(new Hmac($this->hmacManager, new NullAdapter(), array(
            'allowedRoutes' => array(
                'POST/'
            )
        )));

        $payload = '';
        $payload .= $requestMethod . "\n";
        $payload .= $requestResourceUri  . "\n";
        $payload .= $requestBody;
        $this->hmacManager->setPayload($payload);

        $this->app->post('/', function () {
        });

        ob_start();

        $this->app->run();

        $output = ob_get_clean();

        $this->assertEquals(200, $this->app->response()->getStatus());
    }

    public function testAuthenticationFailure()
    {
        $this->markTestSkipped('skipped');

        $this->setExpectedException('IC\SlimAuthHmac\Exception\HttpForbiddenException');

        $requestMethod = 'POST';
        $requestResourceUri = '/';
        $requestBody = json_encode(array(
            'test' => true
        ));

        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $requestMethod,
            'PATH_INFO' => $requestResourceUri,
            'SERVER_NAME' => 'local.dev',
            'HTTP_CONTENT-TYPE' => 'application/json',
            'slim.input' => $requestBody
        ));

        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));

        $this->app->add(new Hmac($this->hmacManager, new NullAdapter(), array()));

        $payload = '';
        $payload .= $requestMethod . "\n";
        $payload .= $requestResourceUri  . "\n";
        $payload .= $requestBody;
        $this->hmacManager->setPayload($payload);

        $this->app->post('/', function () {
        });

        $this->app->run();

        $this->assertEquals(200, $this->app->response()->getStatus());
    }

    public function testMiddleware()
    {
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

        $this->app = new \Slim\Slim(array(
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing'
        ));

        ob_start();

        // $this->app->add(new ContentTypes());
        $this->app->add(new Hmac($this->hmacManager, new NullAdapter()));

        $this->app->post('/', function () {
        });

        $this->app->run();

        $output = ob_get_clean();

        $this->assertEquals(200, $this->app->response()->getStatus());
    }
}
