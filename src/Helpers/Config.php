<?php

namespace App\Helpers;

class Config
{
    public static function getConfig()
    {
        $config = dirname(__DIR__, 2) . '\config\config.php';

        if (is_file($config)) {
            return require_once($config);
        }
        return [];
    }
}