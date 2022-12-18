<?php

namespace App\Controller;

use App\Core\Controller;

class UserController extends Controller
{
    public function index()
    {
        $this->view->render('user-profile');
    }

}