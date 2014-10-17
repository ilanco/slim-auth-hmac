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
        $this->hmacManager = new HmacManager();
    }

    public function call()
    {
        if ($this->checkRequest()) {
            $this->next->call();
        }
    }

    private function checkRequest()
    {
        $isValid = false;

        $app = $this->app;

        $headers = $app->request->headers();

        // get api key and hash from headers
        $authString = $headers->get('authentication');

        if (strpos($authString, 'hmac ') !== 0) {
            $app->response()->setStatus(403);
        }
        else {
            $authString = substr($authString, 5);
            $authArray = explode(':', $authString);

            if (count($authArray) !== 2) {
                $app->response()->setStatus(403);
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
                    $app->response()->setStatus(403);
                }
            }
        }

        return $isValid;
    }
}
