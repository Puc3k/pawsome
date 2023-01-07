<?php

namespace App\Core;

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\RankingController;
use App\Controller\UserController;
use App\Controller\QuizController;
use App\Helpers\Session;
use JetBrains\PhpStorm\NoReturn;

class App
{
    private const DEFAULT_ACTION = 'home';
    protected ?Request $request;

    public function __construct()
    {
        //Start sesji
        Session::init();
        $this->request = new Request($_GET, $_POST, $_SERVER);
    }

    private function parseUrl(): array
    {
        if ($uri = $this->request->getParam('page', self::DEFAULT_ACTION)) {

            // Usunięcie białych znaków, sanitizacja, wydobycie akcji
            return explode("/", filter_var(rtrim($uri, "/"), FILTER_SANITIZE_URL));
        }
        // Usunięcie białych znaków, sanitizacja, domyslna akcja
        return explode('/', filter_var(rtrim('home', "/"), FILTER_SANITIZE_URL));
    }

    public function run(): void
    {
        //Flash alerts, remove from session
        Session::delete('error');
        Session::delete('success');
        Session::delete('breed-seed');
        Session::delete('image-seed');

        //Wywołanei metody do pobrania i parsowania URL
        $url = $this->parseUrl();
        //Prosty routing - w zależności od url wywoływany jest odpowiedni kontroler i metoda
        match ($url[0]) {
            'home' => (new HomeController)->index(),
            'ranking' => (new RankingController())->getQuizzesData(),
            'ranking-user' => (new RankingController())->getQuizzesDataForUser(),
            'ranking-admin' => (new RankingController())->getQuizzesDataForAdmin(),
            'quiz' => (new QuizController)->quiz(),
            'login' => (new AuthController)->login(),
            'register' => (new AuthController)->register(),
            'logout' => (new AuthController)->logout(),
            'user-profile' => (new UserController())->index(),
            'edit-user-profile' => (new UserController())->editProfile(),
            'users-list' => (new UserController())->getUsersList(),
            'update-images' => (new UserController())->seedImages(),
            default => $this->actionNotFound() //Jeśli nie znalazło strony to strona 404
        };
    }

    #[NoReturn] private function actionNotFound(): void
    {
        header('HTTP/1.1 404 Not Found');
        die();
    }
}