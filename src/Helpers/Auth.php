<?php

namespace App\Helpers;

class Auth
{
    public static function guest(): bool
    {
        //Check if user has no role and is not logged in
        if (!Session::exists('role') && !Session::exists('logged')) {
            return true;
        }
        return false;
    }

    public static function user(): bool
    {
        //Check if user is logged and has role = 'user'
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
        //Check if user is logged and has role = 'admin'
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
        //Check if user is logged and has any role
        if (!Session::exists('role') && !Session::exists('logged')) {
            return false;
        }
        return true;
    }

    public static function validFormPostData(array $data): array
    {
        //Sanitize post form data, remove special chars, check format
        $data['userName'] = filter_var($_POST['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $data['createPassword'] = filter_var($_POST['createPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['confirmPassword'] = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $data ?? [];
    }

    public static function checkIfFormDataErrors(array $data): void
    {
        if ($data) {
            //Check input values if it is missing set error
            if (!$data['userName']) {
                Session::put('error', 'Podaj nazwę użytkownika');
            } elseif (!$data['email']) {
                Session::put('error', 'Podaj email');
            } elseif (strlen($data['createPassword']) < 8 || strlen($data['confirmPassword']) < 8) {
                Session::put('error', 'Hasło powinno mieć min 8 znaków');
            } elseif ($data['createPassword'] !== $data['confirmPassword']) {
                Session::put('error', 'Hasła nie są jednakowe');
            }
        }
    }
}
