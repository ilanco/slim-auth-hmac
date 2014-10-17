<?php

namespace IC\Slim\Middleware\Auth;

use IC\Animaly\Auth\HmacManager;

class Hmac extends \Slim\Middleware
{
    private $hmacManager = null;

    public function __construct(array $hashes, array $options = array())
    {
        $this->hmacManager = new HmacManager();
    }

    public function call()
    {
        $this->checkRequest();

        $this->next->call();

        $this->setResonse();
    }

    private function checkRequest()
    {
        $app = $this->app;

        $headers = $app->request->headers();

        // get api key and hash from headers
        // $this->parseAuthHeader($headers->get('authentication'));
        $authString = $headers->get('authentication');

        if (strpos($authString, 'hmac ') !== 0) {
            $app->hook('slim.after.router', function () use ($app) {
                $app->halt(403);
            });
        }
        else {
            $authString = substr($authString, 5);
            $authArray = explode(':', $authString);

            if (count($authArray) !== 2) {
                $app->hook('slim.after.router', function () use ($app) {
                    $app->halt(403);
                });
            }
            else {
                list($publicKey, $hmacHash) = $authArray;

                $this->hmacManager->setPublicKey($publicKey);
                $this->hmacManager->setHmacHash($hmacHash);
                $payload = '';
                $payload .= $app->request->getMethod() . "\n";
                $payload .= $app->request->getResourceUri() . "\n";
                $payload .= $app->request()->getBody();
                $this->hmacManager->setPayload($payload);

                $hmacValue = $this->hmacManager->generateHmac();
                $isValid = $this->hmacManager->isValid($this->hmacManager->generateHmac(), $hmacHash);

                if ($isValid !== true) {
                    $app->hook('slim.after.router', function () use ($app) {
                        $app->halt(403);
                    });
                }
            }
        }
    }

    private function setResonse()
    {
        $app = $this->app;

        $headers = $app->request->headers();
    }
}
