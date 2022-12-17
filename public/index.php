<?php

//Autoloader
spl_autoload_register(function (string $name) {
    $name = str_replace(['\\', 'App/'], ['/', ''], $name);
    $path = "../src/$name.php";
    require_once($path);
});

//Dodatkowe narzedzie do debugowania dd();
require_once("../src/Utils/debug.php");

//Bootloader, wywoałnie głównej klasy
$app = new App\Core\App;
$app->run();


