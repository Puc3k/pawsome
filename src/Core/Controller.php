<?php

namespace App\Core;

use App\Helpers\Config;
use JetBrains\PhpStorm\NoReturn;

class Controller
{
    protected View $view;
    protected ?Request $request;
    protected array|false $config;


    public function __construct()
    {
        $this->config = Config::getConfig();
        $this->view = new View();
        $this->request = new Request($_GET, $_POST, $_SERVER);

    }

    #[NoReturn] public function redirect(string $uri): void
    {
        header("Location: $uri");
        exit();
    }
}