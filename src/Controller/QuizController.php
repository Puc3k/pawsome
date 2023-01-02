<?php

namespace App\Controller;

use App\Core\Controller;
use App\Helpers\Database;
use App\Helpers\Session;
use PDO;
use Throwable;

class QuizController extends Controller
{
    private int $maxRound = 16;

    public function quiz()
    {
        Session::init();
        $round = Session::exists('quiz-round') ? Session::get('quiz-round') : 1;

        $checkIsAnswer = $this->request->postParam('round-dog-img'); //User POST send id, battle winner
        //If user send POST
        if (!empty($checkIsAnswer) && $this->request->isPost()) {
            $quizImages = $this->handleUserAnswer($checkIsAnswer);
            var_dump($quizImages);
            if (!$quizImages) {
                //Todo sprawdzić dlaczego nie kieruje na główną
                $this->view->render('index');
            }

            $isWinner = $this->isWinner($quizImages); //Check if is a winner - true/false

            if ($isWinner) {
                $viewData = [
                    'winner' => $this->getWinner($quizImages) //Pass winner to view
                ];

                Session::destroy(); //End current quiz, clear session data
                $this->view->render('quiz', $viewData);
            }

            $round++;
            Session::put('quiz-images', $quizImages);
        }

        //Quiz init
        if ($round == 1 && !Session::exists('round-dog-img')) {
            try {
                $images = $this->generateQuizImages();
                if ($images) {
                    Session::put('quiz-images', $images);
                }

            } catch (Throwable) {
                print_r('Błąd podczas łączenia z bazą danych!');
            }
        }

        $viewData = [];
        $images = Session::get('quiz-images');

        if ($images) {
            $roundImages = $this->generateRound($images);

            if (!$roundImages) {
                $this->view->render('index', ['error' => 'Błąd podczas generowania Quizu, spróbuj ponownie.']);
            }

            if (count($roundImages) > 1) {
                Session::put('round-dog-img', [$roundImages[0]['id'], $roundImages[1]['id']]);
            }

            $viewData += [
                'img1' => $roundImages[0] ?? '',
                'img2' => $roundImages[1] ?? ''
            ];
        }

        Session::put('quiz-round', $round);

        $viewData += [
            'round' => $round
        ];

        $this->view->render('quiz', $viewData);
    }

    private function generateQuizImages(): array
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT * FROM breed_images ORDER BY rand() LIMIT ?');
        $query->bindParam(1, $this->maxRound, PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetchAll();

        foreach ($data as &$row) {
            $row['status'] = 1;
        }

        return $data ?? [];
    }

    private function generateRound(array $data): bool|array
    {
        if ($data && count($data) > 1) {
            $newData = array_filter($data, function ($data) {
                if ($data['status'] == 1) {
                    return $data;
                }
            });
            return array_slice($newData, 0, 2);
        }
        return false;
    }

    private function isWinner(array $data): bool
    {
        $winner = false;
        $countStatus = array_count_values(array_column($data, 'status'));
        if ($countStatus[1] == 1) {
            $winner = true;
        }
        return $winner;

    }

    private function getWinner(array $data): array
    {
        $newData = array_filter($data, function ($data) {
            $winner = [];
            if ($data['status'] == 1) {
                $winner = $data;
            }
            return $winner;
        });

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

        $winnerImage = array_search($answer, $battleImages); //Winner image
        unset($battleImages[$winnerImage]); //Remove winner image from battle array
        $rejectedImage = reset($battleImages); //Get key of rejected image
        //Set rejected image status to 0
        return $this->changeStatus($quizImages, $rejectedImage);
    }

    private function changeStatus(array $data, int $id): array
    {
        foreach ($data as &$row) {
            if ($row['id'] == $id) {
                $row['status'] = 0;
            }
        }
        return $data;
    }
}