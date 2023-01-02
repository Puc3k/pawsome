<?php

namespace App\Controller;

use App\Core\Controller;

class QuizController extends Controller
{
    public function index()
    {
        $this->checkIsUserLogged('admin');

    }

    public function quiz()
    {
        $this->view->render('quiz');
    }
    
}