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

    public function getBreedList(): void
    {
        $breedList = $this->callApi('breeds/list/all');
        if (!empty($breedList) && $breedList['status'] == 'success') {
            $db = Database::getInstance()->getConnection();
            foreach ($breedList['message'] as $breed => $subBreed) {
                if (is_array($subBreed) && count($subBreed) > 0) {
                    foreach ($subBreed as $singleSubBreed) {
                        $query = $db->prepare('INSERT INTO breed_list (breed, sub_breed) VALUES (:breed, :subBreed)');
                        $query->execute([
                            'breed' => $breed,
                            'subBreed' => $singleSubBreed
                        ]);
                    }
                } else {
                    $query = $db->prepare('INSERT INTO breed_list (breed) VALUES (:breed)');
                    $query->execute([
                        'breed' => $breed
                    ]);
                }
            }
        }
    }

    public function getBreedsImages(): void
    {
        $db = Database::getInstance()->getConnection();
        $query = $db->prepare('SELECT * FROM breed_list');
        $query->execute();
        $breeds = $query->fetchAll();
        if (is_array($breeds) && count($breeds) > 0) {
            $inserts = [];
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
                        $inserts = [
                            ':breed_id' => $breed['id'],
                            ':image' => $breedImage
                        ];
                        if (count($inserts) > 0) {
                            $query = $db->prepare('INSERT INTO breed_images (breed_id, image) VALUES (:breed_id, :image)');
                            $query->execute($inserts);
                        }
                    }
                }
            }
        }
    }

    public function getFactsAboutDogs(int $numberOfFacts = 0): array
    {
        $dogFacts = [];
        for ($i = 0; $i <= $numberOfFacts; $i++) {
            $dogFactResult = $this->callApi('facts');
            if (!empty($dogFactResult)) {
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

//    public function storeFacts(array $facts)
//    {
//        if ($facts && count($facts) > 1) {
//            foreach ($facts as $fact) {
//                try {
//                    $db = Database::getInstance()->getConnection();
//                    $query = $db->prepare('INSERT INTO dog_facts (fact_id, fact) VALUES (:fact_id, :fact)');
//
//                    $query->execute([
//                        'fact_id' => $fact['id'],
//                        'fact' => $fact['fact']
//                    ]);
//                } catch (Throwable) {
//                }
//            }
//        }
//    }

}