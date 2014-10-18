<?php

/**
 * This file is part of the SlimAuthHmac package.
 *
 * (c) Ilan Cohen <ilanco@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IC\SlimAuthHmac\Auth;

/**
 * HMAC Manager computes the HMAC and validates the payload.
 *
 * @author Ilan Cohen <ilanco@gmail.com>
 */
class HmacManager
{
    const DEFAULT_ALGORITHM = 'sha256';

    private $options;

    private $algorithm;

    private $publicKey;

    private $privateKey;

    private $ttl;

    private $timestamp;

    private $hmacHash;

    private $payload;

    /**
     * Constructor.
     *
     * @param array $options List of options
     */
    public function __construct(array $options = array())
    {
        $defaults = array(
            'algorithm' => self::DEFAULT_ALGORITHM,
            'privateKey' => null
        );

        $this->options = array_merge($defaults, $options);

        if (!empty($this->options['algorithm'])) {
            $this->setAlgorithm($this->options['algorithm']);
        }

        if (!empty($this->options['privateKey'])) {
            $this->setPrivateKey($this->options['privateKey']);
        }
    }

    /**
     * Sets the algorithm.
     *
     * @param string $algorithm The algorithm used to compute the HMAC
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * Gets the algorithm.
     *
     * @return string $algorithm The algorithm used to compute the HMAC
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Sets the public key.
     *
     * @param string $publicKey The public key to identify the client
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * Gets the public key.
     *
     * @return string $publicKey The public key to identify the client
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Sets the private key.
     *
     * @param string $privateKey The private key to hash the message with
     */
    public function setPrivateKey($privateKey)
    {
        if (empty($privateKey)) {
            throw new \RuntimeException('Private key must be set and not empty');
        }

        $this->privateKey = $privateKey;
    }

    /**
     * Gets the private key.
     *
     * @return string $privateKey The private key
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function setTtl($ttl)
    {
        $this->ttl = intval($ttl);
    }

    public function getTtl()
    {
        return $this->ttl;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = intval($timestamp);
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setHmacHash($hmacHash)
    {
        $this->hmacHash = $hmacHash;
    }

    public function getHmacHash()
    {
        return $this->hmacHash;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function generateHmac()
    {
        if (empty($this->getAlgorithm())) {
            throw new \RuntimeException('Algorithm must be set and not empty');
        } elseif (empty($this->getPrivateKey())) {
            throw new \RuntimeException('Private key must be set and not empty');
        }

        $hash = hash_hmac($this->getAlgorithm(), $this->getPayload(), $this->getPrivateKey(), false);

        return $hash;
    }

    public function isValid($hash1, $hash2)
    {
        return ($hash1 === $hash2);
    }
}
