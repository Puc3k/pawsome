<?php

namespace App\Core;

class Helper
{
    public static function getHostname(): string
    {
        $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';

        return $protocol . '://' . $_SERVER['HTTP_HOST'];
    }
}