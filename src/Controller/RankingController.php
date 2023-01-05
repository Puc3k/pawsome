<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Database;
use App\Helpers\Session;
use App\Model\User;

class RankingController extends Controller
{
    public function ranking()
    {
        $this->view->render('ranking');
    }
    public function ranking_user()
    {
        $this->view->render('ranking-user');
    }
    public function ranking_admin()
    {
        $this->view->render('ranking-admin');
    }
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
        $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id desc limit 10');
        $query->execute();
        $quizzesData = $query->fetchAll();
        $quizzesCount=$this->getCountQuizzes();
        $rankingData=[];
        foreach ($quizzesData as $quizData) {
            $rankingData += [
                'precent_winning' => ($quizData['count'] / $quizzesCount)*100, //Pass precent winning to view
                'image' => $quizData['image'] //Pass image winner to view
            ];
        }
        $this->view->render('ranking', ['rankingData' => $rankingData]);    
    }

    public function getQuizzesDataForUser(): void
    {
        $userId = User::gerUserIdFromSession() ?? NULL;
        if($userId)
        {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id HAVING quizzes.user_id=$userId');
            $query->execute();
            $quizzesData = $query->fetchAll();
            $quizzesCount=$this->getCountQuizzes();
            $rankingData=[];
            foreach ($quizzesData as $quizData) {
                $rankingData += [
                    'precent_winning' => ($quizData['count'] / $quizzesCount)*100, //Pass precent winning to view
                    'image' => $quizData['image'] //Pass image winner to view
                ];
            }
                $this->view->render('ranking-user', ['rankingData' => $rankingData]);    
        }
        Session::put('error', 'Brak identyfikatora użytkownika');
    }

    public function getQuizzesDataForAdmin(): void
    {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id');
            $query->execute();
            $quizzesData = $query->fetchAll();
            $quizzesCount=$this->getCountQuizzes();
            $rankingData=[];
            foreach ($quizzesData as $quizData) {
                $rankingData += [
                    'precent_winning' => ($quizData['count'] / $quizzesCount)*100, //Pass precent winning to view
                    'image' => $quizData['image'] //Pass image winner to view
                ];
            }
                $this->view->render('ranking-admin', ['rankingData' => $rankingData]);    
    }
}