<?php

namespace IC\Slim\Auth;

class HmacManager
{
    const DEFAULT_ALGORITHM = 'sha256';

    private $algorithm;

    private $publicKey;

    private $privateKey;

    private $ttl;

    private $timestamp;

    private $hmacHash;

    private $payload;

    public function __construct($algorithm = self::DEFAULT_ALGORITHM, $privateKey = null)
    {
        $this->setAlgorithm($algorithm);
        $this->setPrivateKey($privateKey);
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

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
        $hash = hash_hmac($this->algorithm, $this->payload, $this->privateKey, false);

        return $hash;
    }

    public function isValid($hash1, $hash2)
    {
        return ($hash1 === $hash2);
    }
}
