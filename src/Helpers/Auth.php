<?php

namespace App\Helpers;

class Auth
{
    public static function init()
    {
        if (!Session::exists('role')) {
            Session::put('role','admin');
        }
        if (!Session::exists('logged')) {
            Session::put('logged',false);
        }
    }

    public static function guest()
    {
        if (Session::exists('role') && Session::exists('logged')) {
            $role = Session::get('role');
            $logged = Session::get('logged');
            if ($logged == false)
                return true;
        }
            return false;
    }

    public static function user()
    {
        if (Session::exists('role') && Session::exists('logged')) {
            $role = Session::get('role');
            $logged = Session::get('logged');
            if ($logged == true&&$role=='user')
                return true;
        }
            return false;
    }

    public static function admin()
    {
        if (Session::exists('role') && Session::exists('logged')) {
            $role = Session::get('role');
            $logged = Session::get('logged');
            if ($logged == true&&$role=='admin')
                return true;
        }
            return false;
    }
}
