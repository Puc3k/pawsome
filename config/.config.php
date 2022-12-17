<?php
//Przykładowy plik konfiguracyjny, usunąć kropke z nazwy!!
const SESSION_LOGGED_USER = 'user'; //sesja jeśli user zalogowany

//Dostępy do bazy danych

return [
    'db' => [
        'host' => 'localhost', //Host
        'database' => 'pawsome', //Database name
        'password' => 'secret', //Secret password
        'user' => 'pawsome' //Database username
    ]
];