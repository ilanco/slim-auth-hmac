<?php

namespace IC\SlimAuthHmac\Exception;

class HttpForbiddenException extends \RuntimeException
{
    /**
     * Constructor
     *
     * @param string    $message  Exception message
     * @param int       $code     Exception code
     * @param Exception $previous Previous exception
     */
    public function __construct(
        $message = 'You are not authorized to access this resource',
        $code = 403,
        \Exception $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
