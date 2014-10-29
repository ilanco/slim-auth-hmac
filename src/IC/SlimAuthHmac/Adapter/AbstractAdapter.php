<?php
/**
 * Abstract authentication adapter
 *
 * @package    SlimAuthHmac
 * @copyright  Copyright (c) 2014 Ilan Cohen <ilanco@gmail.com>
 * @license    https://raw.githubusercontent.com/ilanco/slim-auth-hmac/master/LICENSE   MIT License
 * @link       https://github.com/ilanco/slim-auth-hmac
 */

namespace IC\SlimAuthHmac\Adapter;

abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var mixed
     */
    protected $credential;

    /**
     * @var mixed
     */
    protected $identity;

    /**
     * Returns the credential of the account being authenticated, or
     * NULL if none is set.
     *
     * @return mixed
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * Sets the credential for binding
     *
     * @param  mixed           $credential
     * @return AbstractAdapter
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    /**
     * Returns the identity of the account being authenticated, or
     * NULL if none is set.
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Sets the identity for binding
     *
     * @param  mixed          $identity
     * @return AbstractAdapter
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }
}
