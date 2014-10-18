<?php

/**
 * This file is part of the SlimAuthHmac package.
 *
 * (c) Ilan Cohen <ilanco@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IC\SlimAuthHmac\Middleware\Auth;

use IC\SlimAuthHmac\Auth\HmacManager;
use IC\SlimAuthHmac\Exception\HttpForbiddenException;

/**
 * HMAC Middleware
 *
 * @author Ilan Cohen <ilanco@gmail.com>
 */
class Hmac extends \Slim\Middleware
{
    private $hmacManager = null;

    /**
     * Constructor.
     *
     * @param string $options List of options
     */
    public function __construct(array $options = array())
    {
        $defaults = array(
            'algorithm' => 'sha256',
            'privateKey' => null
        );

        $this->hmacManager = new HmacManager(array_merge($defaults, $options));
    }

    public function call()
    {
        $app = $this->app;
        $hmacManager = $this->hmacManager;

        $checkRequest = function () use ($app, $hmacManager) {
            $headers = $app->request->headers();

            // get api key and hash from headers
            $authString = $headers->get('authentication');

            if (strpos($authString, 'hmac ') !== 0) {
                throw new HttpForbiddenException();
            }
            else {
                $authString = substr($authString, 5);
                $authArray = explode(':', $authString);

                if (count($authArray) !== 2) {
                    throw new HttpForbiddenException();
                }
                else {
                    list($publicKey, $hmacHash) = $authArray;

                    $this->hmacManager->setPublicKey($publicKey);
                    $this->hmacManager->setHmacHash($hmacHash);
                    $payload = '';
                    $payload .= $app->request->getMethod() . "\n";
                    $payload .= $app->request->getResourceUri() . "\n";

                    $body = $app->request()->getBody();
                    if (is_string($body)) {
                        $body = @json_decode($body);
                    }
                    $payload .= json_encode($body);
                    $this->hmacManager->setPayload($payload);

                    $hmacValue = $this->hmacManager->generateHmac();
                    $isValid = $this->hmacManager->isValid($this->hmacManager->generateHmac(), $hmacHash);

                    if ($isValid !== true) {
                        throw new HttpForbiddenException();
                    }
                }
            }
        };

        $this->app->hook('slim.before.dispatch', $checkRequest);

        $this->next->call();
    }
}
