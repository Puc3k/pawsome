<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Database;

class RankingController extends Controller
{
    public function getCountQuizzes(): int
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT count(id) as ilosc FROM `quizzes`;');
        $query->execute();
        $countQuizzes = $query->fetchAll();
        return $countQuizzes ?? 0;
    }

    public function getQuizzesData(): void
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id;');
        $query->execute();
        $quizzesData = $query->fetchAll();
        $quizzesCount=$this->getCountQuizzes();
        $viewData=[];
        foreach ($quizzesData as $quizData) {
            $viewData += [
                'precent_winning' => $quizData['count'] / $quizzesCount, //Pass precent winning to view
                'image' => $quizData['image'] //Pass image winner to view
            ];
        }
            $this->view->render('ranking', $viewData);       
    }
}