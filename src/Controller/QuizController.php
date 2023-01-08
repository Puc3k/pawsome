<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Database;
use App\Helpers\Session;
use App\Model\User;
use PDO;
use Throwable;

class QuizController extends Controller
{
    private int $maxRound = 16;

    public function quiz()
    {
        //Init session if not exist
        Session::init();
        $viewData = [];
        $roundImages = [];
        //Get images if exist in session
        $images = Session::get('quiz-images');
        //Check if round exist in session if no set value to 1
        $round = Session::exists('quiz-round') ? Session::get('quiz-round') : 1;
        //If there is first round get random 16 images from data and put it to session
        if (!$images && ($round == 1 && !Session::exists('round-dog-img'))) {
            $images = $this->startQuiz();
            //Generate first round A vs B
            $roundImages = $this->generateRound($images);
        }
        //Check if user choose one img (sent post param)
        $checkIsAnswer = $this->request->postParam('round-dog-img'); //User POST send id, battle winner

        //If user send POST
        if (!empty($checkIsAnswer)) {
            $quizImages = $this->handleUserAnswer($checkIsAnswer);
            if (!$quizImages) {
                //If error return index
                $this->view->render('index');
            }
            //Round increment
            $round++;
            //Put data to session
            Session::put('quiz-round', $round);
            Session::put('quiz-images', $quizImages);
            $images = Session::get('quiz-images');
        }

        //check if winner
        $isWinner = $this->isWinner($images); //Check if is a winner - true/false
        if ($isWinner) {
            $viewData = [
                'winner' => $this->getWinner($images) //Pass winner to view
            ];
            //Save quiz in db
            $this->saveQuiz($viewData['winner']);

            Session::delete('quiz-round'); //End current quiz, clear session data
            Session::delete('round-dog-img'); //End current quiz, clear session data
            Session::delete('quiz-images'); //End current quiz, clear session data

            $this->view->render('quiz', $viewData);
        }

        if (!$roundImages) {
            $roundImages = $this->generateRound($images);
        }

        if (count($roundImages) > 1) {
            //Put round images into session
            Session::put('round-dog-img', [$roundImages[0]['id'], $roundImages[1]['id']]);
        } else {
            Session::put('error', 'Błąd podczas generowania Quizu, spróbuj ponownie.');
            $this->view->render('index');
        }

        $viewData += [
            'img1' => $roundImages[0] ?? '',
            'img2' => $roundImages[1] ?? '',
            'round' => $round
        ];

        $this->view->render('quiz', $viewData);
    }

    private function startQuiz(): array
    {
        try {
            //Get 16 images from db
            $images = $this->generateQuizImages();
            if ($images) {
                //Put them in session
                Session::put('quiz-images', $images);
                return $images;
            }

        } catch (Throwable) {
            Session::put('error', 'Błąd podczas łączenia z bazą danych!');
            $this->view->render('index');
        }
        return [];
    }

    private function generateQuizImages(): array
    {
        //Get 16 images from db by random order
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT * FROM breed_images ORDER BY rand() LIMIT ?');
        $query->bindParam(1, $this->maxRound, PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetchAll();
        //Set all statuses to 1 (not rejected yet)
        foreach ($data as &$row) {
            $row['status'] = 1;
        }

        return $data ?? [];
    }

    private function generateRound(array $data): bool|array
    {
        if ($data && count($data) > 1) {
            //Get only images with status 1  by array filter function
            $newData = array_filter($data, function ($data) {
                if ($data['status'] == 1) {
                    return $data;
                }
                return [];
            });
            //Return first 2 elements from array where status is equal 1
            return array_slice($newData, 0, 2);
        }
        return false;
    }

    private function isWinner($data): bool
    {
        $winner = false;
        if (is_array($data)) {
            //Count status value if there is only one with status = "1" than return true (that's mean there is a winner in array)
            $countStatus = array_count_values(array_column($data, 'status'));
            if ($countStatus[1] == 1) {
                $winner = true;
            }
        }
        return $winner;

    }

    private function getWinner(array $data): array
    {
        //Filter array and get one where status is equal to 1
        $newData = array_filter($data, function ($data) {
            $winner = [];
            if ($data['status'] == 1) {
                $winner = $data;
            }
            return $winner;
        });
        //Merge and return array
        return array_merge(...$newData) ?? [];
    }

    private function handleUserAnswer(string $answer): bool|array
    {
        $quizImages = Session::get('quiz-images'); //All current quiz images
        $battleImages = Session::get('round-dog-img'); //Two images from battle A vs B

        //If $battleImages is NULL - data in session empty
        if (is_null($battleImages)) {
            return false;
        }

        $winnerImage = array_search($answer, $battleImages); //Get winner image

        unset($battleImages[$winnerImage]); //Remove winner image from battle array

        $rejectedImage = reset($battleImages); //Get key of rejected image

        //Set rejected image status to 0
        return $this->changeStatus($quizImages, $rejectedImage);
    }

    private function changeStatus(array $data, int $id): array
    {
        //Change recjeted image status to 0
        foreach ($data as &$row) {
            if ($row['id'] == $id) {
                $row['status'] = 0;
            }
        }
        return $data;
    }

    private function saveQuiz(array $winner): void
    {
        //Save winner_id image to db
        try {
            $db = Database::getInstance()->getConnection();
            $query = $db->prepare('INSERT INTO quizzes (user_id, winner_id) VALUES (:userId, :winnerId)');
            $query->execute([
                'userId' => User::getUserIdFromSession() ?? 0,
                'winnerId' => $winner['id']
            ]);
        } catch (Throwable) {
            Session::put('error', 'Błąd podczas zapisu do bazy danych.');
            $this->view->render('index');
        }
    }
}