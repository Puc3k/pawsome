<?php

namespace App\Controller;

use App\Request;
use App\View;
use JetBrains\PhpStorm\Pure;

class HomeController
{
    protected const DEFAULT_ACTION = 'list';
    private View $view;
    private Request $request;

    #[Pure] public function __construct(Request $request)
    {
        $view = $this->view = new View();
        $this->request = $request;
    }

    public function run()
    {
        $this->view->render('index');
    }
}