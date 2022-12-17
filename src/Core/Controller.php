<?php

namespace App\Core;

use App\Helpers\Session;

class Controller
{
    private const DEFAULT_ACTION = 'list';
    protected $view = null;
    protected $request = null;

    public function __construct()
    {
        //Start sesji
        Session::init();

        $this->view = new View();
        $this->request = new Request($_GET, $_POST, $_SERVER);

    }

    public function checkIsUserLogged()
    {
        if (!Session::exists(SESSION_LOGGED_USER)) {
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

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}