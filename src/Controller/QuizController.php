<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Database;
use App\Helpers\Session;

class QuizController extends Controller
{
    private int $round = 0;
    private int $maxRound = 16;

    public function index()
    {
        $this->checkIsUserLogged('admin');

    }

    public function quiz()
    {
        dd($this->request->hasPost());
        Session::init();
        $round = Session::exists('quiz-round');
        $this->round = $round ? Session::get('quiz-round') : 1;
        if ($this->round == 1) {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT * FROM breed_images ORDER BY rand() limit :limit');
            $query->execute([
                'limit' => $this->maxRound
            ]);

            $data = $query->fetchAll();
            dd($data);
        }
        Session::put('quiz-round', $this->round);

        dd(Session::get('quiz-round'));
        dd($this->round);

        $this->view->render('quiz');
    }

}