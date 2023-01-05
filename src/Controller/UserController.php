<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Model\User;

class UserController extends Controller
{
    public function index()
    {
        $userId = User::getUserIdFromSession();

        $this->view->render('user-profile');
    }

    public function getUsersList()
    {
        if (Auth::admin()) {
            $usersList = User::getAllUsers();
            $this->view->render('users-list', ['usersList' => $usersList]);
        }
        $this->view->render('index');
    }

}