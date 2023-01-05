<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Auth;
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
        $query = $db->prepare('SELECT COUNT(id) as ilosc FROM `quizzes`;');
        $query->execute();
        $countQuizzes = $query->fetchAll();
        return $countQuizzes[0]['ilosc'] ?? 0;
    }

    public function getCountQuizzesForUser(): int
    {
        $userId = User::getUserIdFromSession() ?? NULL;
        if ($userId) {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT COUNT(id) as ilosc FROM `quizzes` WHERE quizzes.user_id=:userId;');
            $query->execute(['userId' => $userId]);
            $countQuizzes = $query->fetchAll();
            return $countQuizzes[0]['ilosc'] ?? 0;
        }
        Session::put('error', 'Brak identyfikatora użytkownika');
        return 0;
    }

    public function getQuizzesData(): void
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id ORDER BY count(`quizzes`.`winner_id`) desc limit 10');
        $query->execute();
        $quizzesData = $query->fetchAll();
        $quizzesCount=$this->getCountQuizzes();
        $rankingData=[];
        foreach ($quizzesData as $quizData) {
            $rankingData[] = [
                'precent_winning' => number_format(($quizData['count'] / $quizzesCount)*100,2,".",""), //Pass precent winning to view
                'image' => $quizData['image'] //Pass image winner to view
            ];
        }
        $this->view->render('ranking', ['rankingData' => $rankingData]);    
    }

    public function getQuizzesDataForUser(): void
    {
        $userId = User::getUserIdFromSession() ?? NULL;
        if($userId && Auth::user())
        {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT quizzes.user_id,`quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id HAVING quizzes.user_id=:userId ORDER BY count(`quizzes`.`winner_id`) desc');
            $query->execute(['userId' => $userId]);
            $quizzesData = $query->fetchAll();
            $quizzesCount=$this->getCountQuizzesForUser();
            $rankingData=[];
            foreach ($quizzesData as $quizData) {
                $rankingData[] = [
                    'precent_winning' => number_format(($quizData['count'] / $quizzesCount)*100,2,".",""), //Pass precent winning to view
                    'image' => $quizData['image'] //Pass image winner to view
                ];
            }
                $this->view->render('ranking-user', ['rankingData' => $rankingData]);    
        }
        Session::put('error', 'Brak identyfikatora użytkownika');
        $this->view->render('index');
    }

    public function getQuizzesDataForAdmin(): void
    {
        if(Auth::admin())
        {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('SELECT `quizzes`.`winner_id`,count(`quizzes`.`winner_id`) as count,breed_images.image FROM `quizzes` INNER JOIN `breed_images` ON quizzes.winner_id = breed_images.id GROUP BY quizzes.winner_id ORDER BY count(`quizzes`.`winner_id`) desc');
            $query->execute();
            $quizzesData = $query->fetchAll();
            $quizzesCount=$this->getCountQuizzes();
            $rankingData=[];
            foreach ($quizzesData as $quizData) {
                $rankingData[] = [
                    'precent_winning' => number_format(($quizData['count'] / $quizzesCount)*100,2,".",""), //Pass precent winning to view
                    'image' => $quizData['image'] //Pass image winner to view
                ];
            }
                $this->view->render('ranking-admin', ['rankingData' => $rankingData]);  
        }
        $this->view->render('index');
    }
}