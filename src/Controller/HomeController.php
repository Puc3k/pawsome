<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $api = new ApiController('https://dogapi.dog/api/v2/');

        $dogFact = $api->getFactsAboutDogs(3);

        $this->view->render('index', [
            'dogFact' => $dogFact
        ]);
    }
}