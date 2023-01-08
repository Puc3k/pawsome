<?php

namespace App\Helpers;

class Config
{
    public static function getConfig(): bool|array
    {
        //Return config ini file, connection to db data
        $config = dirname(__DIR__, 2) . '/config/config.ini';

        if (is_file($config)) {
            return parse_ini_file($config, true);
        }
        return [];
    }
}