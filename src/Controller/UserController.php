<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\User;

class UserController extends Controller
{
    public function index()
    {
        $userId = User::gerUserIdFromSession();

        $this->view->render('user-profile');
    }

}