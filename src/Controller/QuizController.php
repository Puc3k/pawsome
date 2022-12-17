<?php

namespace App\Core;

class QuizController
{
    public function index()
    {
        $this->checkIsUserLogged('admin');

    }
    
}