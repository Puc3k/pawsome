<?php

namespace App\Core;

use App\Controller\AuthController;
use App\Controller\HomeController;
use JetBrains\PhpStorm\NoReturn;

class App
{
    private const DEFAULT_URI = '/';
    protected ?Request $request;

    private array $params = [];

    public function __construct()
    {
        $this->request = new Request($_GET, $_POST, $_SERVER);

    }

    private function parseUrl()
    {
        if ($uri = $this->request->getUri()) {

            // Usunięcie białych znaków, sanitizacja, wydobycie akcji
            $this->params = explode("/", filter_var(rtrim($uri, "/"), FILTER_SANITIZE_URL));
        }
    }

    public function run()
    {
        $uri = $this->parseUrl();

        $uri = empty($uri) ? self::DEFAULT_URI : $uri;

        match ($uri[0]) {
            '/' => (new HomeController)->index(),
            '/ranking' => (new HomeController())->ranking(),
            '/quiz' => (new QuizController)->ranking(),
            '/login' => (new AuthController)->login($_POST),
            default => actionNotFound()
        };

    }

    #[NoReturn] private function actionNotFound()
    {
        header('HTTP/1.1 404 Not Found');
        die();
    }
}