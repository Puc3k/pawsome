<?php

namespace App\Helpers;

class Auth
{
    public static function guest(): bool
    {
        if (!Session::exists('role') && !Session::exists('logged')) {
            return true;
        }
        return false;
    }

    public static function user(): bool
    {
        if (Session::exists('role') && Session::exists('logged')) {
            $role = Session::get('role');
            $logged = Session::get('logged');
            if ($logged && $role == 'user')
                return true;
        }
        return false;
    }

    public static function admin(): bool
    {
        if (Session::exists('role') && Session::exists('logged')) {
            $role = Session::get('role');
            $logged = Session::get('logged');
            if ($logged && $role == 'admin')
                return true;
        }
        return false;
    }

    public static function isUserLogged(): bool
    {
        if (!Session::exists('role') && !Session::exists('logged')) {
            return false;
        }
        return true;
    }

}
