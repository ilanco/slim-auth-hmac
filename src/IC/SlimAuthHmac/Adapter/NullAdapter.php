<?php
/**
 * Authentication adapter
 *
 * @package    SlimAuthHmac
 * @copyright  Copyright (c) 2014 Ilan Cohen <ilanco@gmail.com>
 * @license    https://raw.githubusercontent.com/ilanco/slim-auth-hmac/master/LICENSE   MIT License
 * @link       https://github.com/ilanco/slim-auth-hmac
 */

namespace IC\SlimAuthHmac\Adapter;

class NullAdapter extends AbstractAdapter
{
    public function __construct()
    {
    }

    public function authenticate()
    {
        return null;
    }
}
