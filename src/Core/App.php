<?php

namespace App\Core;

use App\Controller\AuthController;
use App\Controller\HomeController;
use JetBrains\PhpStorm\NoReturn;

class App
{
    private const DEFAULT_ACTION = 'home';
    protected ?Request $request;
    
    public function __construct()
    {
        $this->request = new Request($_GET, $_POST, $_SERVER);
        $url = $this->parseUrl();

    }

    private function parseUrl()
    {
        if ($uri = $this->request->getParam('page', self::DEFAULT_ACTION)) {

            // Usunięcie białych znaków, sanitizacja, wydobycie akcji
            return explode("/", filter_var(rtrim($uri, "/"), FILTER_SANITIZE_URL));
        }

    }

    public function run()
    {
        $url = $this->parseUrl();

        match ($url[0]) {
            'home' => (new HomeController)->index(),
            'ranking' => (new HomeController())->ranking(),
            'quiz' => (new QuizController)->ranking(),
            'login' => (new AuthController)->login($_POST),
            default => $this->actionNotFound()
        };

    }

    #[NoReturn] private function actionNotFound()
    {
        header('HTTP/1.1 404 Not Found');
        die();
    }
}