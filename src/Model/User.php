<?php

namespace App\Model;

use App\Helpers\Auth;
use App\Helpers\Database;
use App\Helpers\Session;
use PDO;

class User
{
    public static function checkIfUserExist(string $userName, string $email): bool
    {
        //check if userName or email already exist
        $db = Database::getInstance()->getConnection();
        //param binding
        $query = $db->prepare('SELECT * FROM users WHERE username=:username OR email=:email');
        $query->execute([
            'username' => $userName,
            'email' => $email
        ]);

        return $query->rowCount() > 0;
    }

    public static function getUser(array $data): bool|array
    {
        $db = Database::getInstance()->getConnection();
        //param binding
        $query = $db->prepare('
                    SELECT * FROM users WHERE email= :email');
        $query->execute([
            'email' => $data['email'],

        ]);

        if ($query->rowCount() == 1) {
            // convert the record into assoc array
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    public static function getUserIdFromSession()
    {
        $isUserLogged = Auth::isUserLogged();
        if (!$isUserLogged || !Session::exists('user-id')) {
            return false;
        }

        return Session::get('user-id');
    }
    public static function getAllUsers(): array
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT * FROM users');
        $query->execute();
        $usersList = $query->fetchAll();
        $users = [];
        foreach ($usersList as $user) {
            $users[] = [
                'username' => $user['username'],
                //Pass username to view
                'email' => $user['email'],
                //Pass email to view
                'avatar' => $user['avatar'],
                //Pass avatar to view
                'role' => $user['role'] //Pass role to view
            ];
        }
        return $users;
    }
}