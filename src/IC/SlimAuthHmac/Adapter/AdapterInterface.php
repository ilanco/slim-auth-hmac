<?php
/**
 * Interface for authentication adapters
 *
 * @package    SlimAuthHmac
 * @copyright  Copyright (c) 2014 Ilan Cohen <ilanco@gmail.com>
 * @license    https://raw.githubusercontent.com/ilanco/slim-auth-hmac/master/LICENSE   MIT License
 * @link       https://github.com/ilanco/slim-auth-hmac
 */

namespace IC\SlimAuthHmac\Adapter;

interface AdapterInterface
{
    public function authenticate();
}
