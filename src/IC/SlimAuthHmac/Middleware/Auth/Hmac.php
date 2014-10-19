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
use IC\SlimAuthHmac\Utils;

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
        $this->app->hook('slim.before.dispatch', array($this, 'checkRequest'));

        $this->next->call();
    }

    public function checkRequest()
    {
        $app = $this->app;

        $authHeader = $app->request->headers()->get('authentication');

        if (strpos(strtoupper($authHeader), 'HMAC ') !== 0) {
            throw new HttpForbiddenException();
        }
        else {
            $authKeySig = substr($authHeader, 5);

            if (count(explode(':', $authKeySig)) !== 2) {
                throw new HttpForbiddenException();
            }
            else {
                list($publicKey, $hmacSignature) = explode(':', $authKeySig);

                $this->hmacManager->setPublicKey($publicKey);
                $this->hmacManager->setHmacHash($hmacSignature);

                $this->hmacManager->setRequestMethod($app->request->getMethod());
                $this->hmacManager->setRequestResourceUri($app->request->getResourceUri());

                $requestBody = $app->request()->getBody();
                if (Utils::isJson($requestBody)) {
                    $requestBody = json_decode($requestBody);
                }
                $this->hmacManager->setRequestBody(json_encode($requestBody));

                $payload = '';
                $payload .= $this->hmacManager->getRequestMethod() . "\n";
                $payload .= $this->hmacManager->getRequestResourceUri() . "\n";
                $payload .= $this->hmacManager->getRequestBody();
                $this->hmacManager->setPayload($payload);

                $hmacValue = $this->hmacManager->generateHmac();
                $isValid = $this->hmacManager->isValid($this->hmacManager->generateHmac(), $hmacSignature);

                if ($isValid !== true) {
                    throw new HttpForbiddenException();
                }
            }
        }
    }
}
