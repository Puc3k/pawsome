<?php

namespace App\Model;

use App\Helpers\Database;

class User
{
    public static function checkIfUserExist(string $userName, string $email): bool
    {
        //check if userName or email already exist
        $db = Database::getInstance()->getConnection();
        //param binding
        $query = $db->prepare('SELECT * users WHERE username=:username OR email=:email');
        $query->execute([
            'username' => $userName,
            'email' => $email
        ]);

        return $query->rowCount() > 0;
    }

}