<?php

namespace IC\SlimAuthHmac;

class Utils
{
    public static function isJson($string)
    {
        $result = false;

        if (
            is_string($string)
            && is_object(@json_decode($string))
            && (json_last_error() == JSON_ERROR_NONE)
        ) {
            $result = true;
        }

        return $result;
    }
}
