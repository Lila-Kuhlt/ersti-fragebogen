<?php

namespace Kuhlt\ErstiFragebogen\Classes\Helpers;

class FormatHelper
{
    public static function arrayKeysToCamelcase($array)
    {
        $keys = preg_replace_callback(
            '/_(.)/',
            fn($m) => strtoupper($m[1]),
            array_keys($array)
        );

        return array_combine($keys, $array);
    }
}
