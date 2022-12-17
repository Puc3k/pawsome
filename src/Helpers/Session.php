<?php

namespace App\Helpers;

class Session
{
    //Jeśli nie ma sesji tworzy nową
    public static function init()
    {
        if (session_id() == "") {
            session_start();
        }
    }

    //Niszczenie sesji
    public static function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE)
        session_destroy();
    }

    //Sprawdzenie czy wartość istnieje w sesji
    public static function exists($key)
    {
        return (isset($_SESSION[$key]));
    }

    //Pobranie wartości z sesji
    public static function get($key)
    {
        if (self::exists($key)) {
            return ($_SESSION[$key]);
        }
    }

    //Dodanie do sesji wartości
    public static function put($key, $value)
    {
        return ($_SESSION[$key] = $value);
    }

    //Usunięcie wartości z sesji
    public static function delete($key): bool
    {
        if (self::exists($key)) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }
}