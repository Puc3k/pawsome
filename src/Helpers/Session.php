<?php

namespace App\Helpers;

class Session
{
    //Init session if is empty
    public static function init(): void
    {
        if (session_id() == "") {
            session_start();
        }
    }

    //Destroy session
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE)
            session_destroy();
    }

    //Check if key exist in session
    public static function exists($key): bool
    {
        return (isset($_SESSION[$key]));
    }

    //Get session value
    public static function get($key)
    {
        if (self::exists($key)) {
            return ($_SESSION[$key]);
        }
        return '';
    }

    //Put key and value to session
    public static function put($key, $value)
    {
        return ($_SESSION[$key] = $value);
    }

    //Delete value from session
    public static function delete($key): bool
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }
}