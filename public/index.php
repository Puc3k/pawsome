<?php

//Autoloader - mechanizm automatycznego ładowania klas dzięki use, dzięki temu nie musimy używać require i include
spl_autoload_register(function (string $name) {
    $name = str_replace(['\\', 'App/'], ['/', ''], $name);
    $path = "../src/$name.php";
    require_once($path);
});

////Dodatkowe narzedzie do debugowania dd(); - tylko na wersji dev
//require_once("../src/Utils/debug.php");

//Bootloader, wywoałnie głównej klasy
$app = new App\Core\App;
$app->run();


