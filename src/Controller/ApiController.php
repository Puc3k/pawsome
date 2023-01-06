<?php

namespace App\Controller;

use App\Helpers\Database;
use CurlHandle;

class ApiController
{
    private string $apiUrl;
    private false|CurlHandle $curl;

    public function __construct(string $apiUrl)
    {
        $this->curl = curl_init();
        $this->apiUrl = $apiUrl;
    }

    private function callApi(string $endpoint)
    {

        $url = $this->apiUrl . $endpoint;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $curlData = curl_exec($this->curl);

        $httpStatus = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        if ($httpStatus !== 200) {
            return ['error' => 'Błąd podczas pobierania danych z  api'];
        }

        curl_close($this->curl);

        return json_decode($curlData, true) ?? [];
    }

    public function getBreedList(): int
    {
        $breedList = $this->callApi('breeds/list/all');
        $insert = 0;
        $notWanted = ['dhole', 'african'];
        if (!empty($breedList) && $breedList['status'] == 'success') {
            $db = Database::getInstance()->getConnection();
            foreach ($breedList['message'] as $breed => $subBreed) {
                if (!in_array($breed, $notWanted)) {
                    if (is_array($subBreed) && count($subBreed) > 0) {
                        foreach ($subBreed as $singleSubBreed) {
                            //Check if breed and sub_breed exist in db
                            $query = $db->prepare('SELECT * FROM breed_list WHERE breed = :breed AND sub_breed = :subBreed');
                            $query->execute([
                                'breed' => $breed,
                                'subBreed' => $singleSubBreed
                            ]);
                            //If no insert new breed
                            if ($query->rowCount() == 0) {
                                $query = $db->prepare('INSERT INTO breed_list (breed, sub_breed) VALUES (:breed, :subBreed)');
                                $query->execute([
                                    'breed' => $breed,
                                    'subBreed' => $singleSubBreed
                                ]);
                                $insert++;
                            }
                        }
                    } else {
                        $db = Database::getInstance()->getConnection();
                        $query = $db->prepare('SELECT * FROM breed_list WHERE breed = :breed');
                        $query->execute([
                            'breed' => $breed,
                        ]);
                        if ($query->rowCount() == 0) {
                            $query = $db->prepare('INSERT INTO breed_list (breed) VALUES (:breed)');
                            $query->execute([
                                'breed' => $breed
                            ]);
                            $insert++;
                        }
                    }
                }
            }
        }
        return $insert;
    }

    public function getBreedsImages(): int
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT * FROM breed_list');
        $query->execute();
        $breeds = $query->fetchAll();
        $insertsCount = 0;
        if (is_array($breeds) && count($breeds) > 0) {
            foreach ($breeds as $breed) {
                if (!empty($breed['sub_breed'])) {
                    $apiQuery = "breed/{$breed['sub_breed']}/images";
                    $breedImages = $this->callApi($apiQuery);
                } elseif (!empty($breed['breed'])) {
                    $apiQuery = "breed/{$breed['breed']}/images";
                    $breedImages = $this->callApi($apiQuery);
                }
                if (isset($breedImages['message']) && $breedImages['status'] == 'success') {
                    foreach ($breedImages['message'] as $breedImage) {
                        //Check if image exist in db
                        $db = Database::getInstance()->getConnection();
                        $query = $db->prepare('SELECT * FROM breed_images WHERE image  = :image');
                        $query->execute([
                            ':image' => $breedImage
                        ]);
                        //If no insert new breed
                        if ($query->rowCount() == 0) {
                            $inserts = [
                                ':breed_id' => $breed['id'],
                                ':image' => $breedImage
                            ];
                            if (count($inserts) > 0) {
                                $query = $db->prepare('INSERT INTO breed_images (breed_id, image) VALUES (:breed_id, :image)');
                                $query->execute($inserts);
                                $insertsCount++;
                            }
                        }
                    }
                }
            }
        }
        return $insertsCount;
    }

    public function getFactsAboutDogs(int $numberOfFacts = 1): array
    {
        $dogFacts = [];
        for ($i = 0; $i < $numberOfFacts; $i++) {
            $dogFactResult = $this->callApi('facts');
            if (!empty($dogFactResult['data']) && is_array($dogFactResult)) {
                foreach ($dogFactResult['data'] as $dogFact) {
                    $dogFacts[] = [
                        'id' => $dogFact['id'],
                        'fact' => $dogFact['attributes']['body']
                    ];
                }
            }
        }
        return $dogFacts;
    }
}