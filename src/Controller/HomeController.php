<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        //Create new Api Controller
        $api = new ApiController('https://dogapi.dog/api/v2/');
        //Get dog facts
        $dogFact = $api->getFactsAboutDogs(3);
        //Return home view with facts
        $this->view->render('index', [
            'dogFact' => $dogFact
        ]);
    }
}