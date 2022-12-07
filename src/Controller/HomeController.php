<?php

namespace App\Controller;

use App\Request;
use App\View;
use JetBrains\PhpStorm\Pure;
use App\Controller\ApiController;

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
        $api = new ApiController();
//        $api->getBreedList();
//        $api->getBreedsImages();
        $this->view->render('index');
    }
}