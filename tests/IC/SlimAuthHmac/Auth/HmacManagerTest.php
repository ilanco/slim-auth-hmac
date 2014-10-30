<?php

namespace Test\IC\SlimAuthHmac\Auth;

use IC\SlimAuthHmac\Auth\HmacManager;

class HmacManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testConstruct()
    {
        $hmacManager = new HmacManager();

        $this->assertInstanceOf('IC\\SlimAuthHmac\\Auth\\HmacManager', $hmacManager);
    }

    public function testAlgorithm()
    {
        $hmacManager = new HmacManager();

        $this->assertEquals($hmacManager::DEFAULT_ALGORITHM, $hmacManager->getAlgorithm());
    }

    public function testKeys()
    {
        $publicKey = sha1(uniqid());
        $privateKey = sha1(uniqid());

        $hmacManager = new HmacManager();
        $hmacManager->setPublicKey($publicKey);
        $hmacManager->setPrivateKey($privateKey);

        $this->assertEquals($publicKey, $hmacManager->getPublicKey());
        $this->assertEquals($privateKey, $hmacManager->getPrivateKey());
    }

    public function testGenerateHmac()
    {
        $algorithm = 'sha256';
        $publicKey = sha1(uniqid());
        $privateKey = sha1(uniqid());
        $payload = sha1(uniqid());
        $payLoadHmacHash = hash_hmac($algorithm, $payload, $privateKey, false);

        $hmacManager = new HmacManager();
        $hmacManager->setAlgorithm($algorithm);
        $hmacManager->setPublicKey($publicKey);
        $hmacManager->setPrivateKey($privateKey);
        $hmacManager->setHmacSignature($payLoadHmacHash);
        $hmacManager->setPayload($payload);
        $hmacValue = $hmacManager->generateHmac();

        $this->assertEquals($hmacValue, $payLoadHmacHash);
    }

    public function testIsValid()
    {
        $algorithm = 'sha256';
        $publicKey = sha1(uniqid());
        $privateKey = sha1(uniqid());
        $payload = sha1(uniqid());
        $payLoadHmacHash = hash_hmac($algorithm, $payload, $privateKey, false);

        $hmacManager = new HmacManager();
        $hmacManager->setAlgorithm($algorithm);
        $hmacManager->setPublicKey($publicKey);
        $hmacManager->setPrivateKey($privateKey);
        $hmacManager->setHmacSignature($payLoadHmacHash);
        $hmacManager->setPayload($payload);
        $hmacValue = $hmacManager->generateHmac();

        $this->assertTrue($hmacManager->isValid($payLoadHmacHash, $hmacValue));
    }
}
