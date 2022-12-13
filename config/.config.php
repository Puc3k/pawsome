<?php
//Przykładowy plik konfiguracyjny, usunąć kropke z nazwy!!
//Start sesji
session_start();

//Dostępy do bazy danych

return [
    'db' => [
        'host' => 'localhost', //Host
        'database' => 'pawsome', //Database name
        'password' => 'secret', //Secret password
        'user' => 'pawsome' //Database username
    ]
];