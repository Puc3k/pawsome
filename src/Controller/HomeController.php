<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
//        $api = new ApiController();
//        $api->getBreedList();
//        $api->getBreedsImages();


        $this->view->render('index');
    }

    public function ranking()
    {
        $this->view->render('ranking');
    }
}