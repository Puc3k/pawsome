<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Model\User;

class UserController extends Controller
{
    public function index()
    {
        $isLogged = Auth::isUserLogged();
        $userId = User::gerUserIdFromSession();


        if (!$isLogged || !$userId) {
            $this->redirect('/');
        }

        $user = User::getUserById($userId);

        $this->view->render('user-profile');
    }

}