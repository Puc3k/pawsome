<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
//        $api = new ApiController('https://dog.ceo/api/');
        $api = new ApiController('https://dogapi.dog/api/v2/');
        $dogFact = $api->getFactsAboutDogs(3);
//        $api->storeFacts($facts);
//        $api->getBreedList();
//        $api->getBreedsImages();


        $this->view->render('index', [
            'dogFact' => $dogFact
        ]);
    }

    public function ranking()
    {
        $this->view->render('ranking');
    }
}