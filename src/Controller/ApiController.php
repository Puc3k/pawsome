<?php

namespace App\Controller;

use App\Helpers\Database;
use App\Helpers\Session;

class ApiController
{
    private string $apiUrl = 'https://dog.ceo/api/';
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
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

}