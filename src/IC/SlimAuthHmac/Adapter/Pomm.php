<?php
/**
 * Pomm authentication adapter
 *
 * @package    SlimAuthHmac
 * @copyright  Copyright (c) 2014 Ilan Cohen <ilanco@gmail.com>
 * @license    https://raw.githubusercontent.com/ilanco/slim-auth-hmac/master/LICENSE   MIT License
 * @link       https://github.com/ilanco/slim-auth-hmac
 */

namespace IC\SlimAuthHmac\Adapter;

class Pomm extends AbstractAdapter
{
    protected $connection = null;

    public function __construct(\Pomm\Connection\Connection $connection)
    {
        $this->connection = $connection;
    }

    public function authenticate()
    {
    }
}

