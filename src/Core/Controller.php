<?php

namespace App\Core;

use App\Helpers\Config;
use App\Helpers\Session;

class Controller
{
    protected const SESSION_LOGGED_USER = 'user'; //sesja jeśli user zalogowany

    protected View $view;
    protected ?Request $request;
    protected $config;


    public function __construct()
    {
        $this->config = Config::getConfig();
        $this->view = new View();
        $this->request = new Request($_GET, $_POST, $_SERVER);

    }

    public function checkIsUserLogged()
    {

        if (!Session::exists(self::SESSION_LOGGED_USER)) {

            $this->view->render('login', [
                'firstName' => Session::get('firstName') ?? null,
                'lastName' => Session::get('lastName') ?? null,
                'userName' => Session::get('userName') ?? null,
                'email' => Session::get('email') ?? null,
                'createPassword' => Session::get('createPassword') ?? null,
                'confirmPassword' => Session::get('confirmPassword') ?? null,
            ]);

            Session::destroy();
        }
    }
}