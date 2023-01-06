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

    public static function validFormPostData(array $data): array
    {
        $data['userName'] = filter_var($_POST['userName'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $data['createPassword'] = filter_var($_POST['createPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['confirmPassword'] = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        return $data ?? [];
    }

    public static function checkIfFormDataErrors(array $data): void
    {
        if ($data) {
            //Check input values
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
