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

    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
}