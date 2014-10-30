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
use IC\SlimAuthHmac\Adapter\AbstractAdapter;
use IC\SlimAuthHmac\Exception\HttpForbiddenException;
use IC\SlimAuthHmac\Utils;

/**
 * HMAC Middleware
 *
 * @author Ilan Cohen <ilanco@gmail.com>
 */
class Hmac extends \Slim\Middleware
{
    private $hmacManager;

    private $adapter;

    private $options;

    /**
     * Constructor.
     *
     * @param string $options List of options
     */
    public function __construct(HmacManager $hmacManager, AbstractAdapter $adapter, array $options = array())
    {
        $this->hmacManager = $hmacManager;

        $this->adapter = $adapter;

        $this->options = array_merge(array(
            'allowedRoutes' => array(),
            'header' => array(
                'authentication' => 'Authentication'
            )
        ), $options);
    }

    public function call()
    {
        $request = $this->app->request();
        $route = $request->getMethod() . $request->getResourceUri();

        if (!$this->isAllowed($route)) {
            $this->app->hook('slim.before.dispatch', array($this, 'checkRequest'));
        }

        $this->next->call();
    }

    public function checkRequest()
    {
        $app = $this->app;

        $authHeader = $app->request->headers()->get($this->options['header']['authentication']);

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
                $this->hmacManager->setHmacSignature($hmacSignature);

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

    private function isAllowed($route)
    {
        $allowed = false;

        foreach ($this->options['allowedRoutes'] as $allowedRoute) {
            $pattern = '|^' . str_replace('*', '.+', $allowedRoute) . '$|';

            if (preg_match($pattern, $route)) {
                $allowed = true;
                break;
            }
        }

        return $allowed;
    }
}
