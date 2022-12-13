<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    protected const DEFAULT_ACTION = 'list';

    public function run()
    {
        $api = new ApiController();
//        $api->getBreedList();
//        $api->getBreedsImages();

        $this->view->render('index');
    }
}