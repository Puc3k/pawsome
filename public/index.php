<?php

spl_autoload_register(function (string $name) {
    $name = str_replace(['\\', 'App/'], ['/', ''], $name);
    $path = "../src/$name.php";
    require_once($path);
});


//Dodatkowe narzedzie do debugowania dd();
require_once("../src/Utils/debug.php");

//Plik konfiguracyjny
$configuration = require_once("../config/config.php");

use App\Controller\HomeController;

$app = new HomeController();

$app->run();


