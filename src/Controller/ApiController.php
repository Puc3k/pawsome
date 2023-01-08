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

    //Metoda która przy wykorzystaniu CURL'a łączy się z podanym w parametrze API
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

    //Metoda do pobierania listy ras
    public function getBreedList(): int
    {
        //Łączenie z API
        $breedList = $this->callApi('breeds/list/all');
        $insert = 0;
        $notWanted = ['dhole', 'african'];
        //Sprawdzenie czy połączenie się udało
        if (!empty($breedList) && $breedList['status'] == 'success') {
            $db = Database::getInstance()->getConnection();
            foreach ($breedList['message'] as $breed => $subBreed) {
                //Usunięcie inny ras psowatych np. hieny
                if (!in_array($breed, $notWanted)) {
                    if (is_array($subBreed) && count($subBreed) > 0) {
                        foreach ($subBreed as $singleSubBreed) {
                            //Sprawdzenie czy rasa i podrasa znajduje się już w bazie
                            $query = $db->prepare('SELECT * FROM breed_list WHERE breed = :breed AND sub_breed = :subBreed');
                            $query->execute([
                                'breed' => $breed,
                                'subBreed' => $singleSubBreed
                            ]);
                            //Jeśli nie, insert do bazy
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
                        //Sprwadzenie czy rasa znajduje się w bazie
                        $db = Database::getInstance()->getConnection();
                        $query = $db->prepare('SELECT * FROM breed_list WHERE breed = :breed');
                        $query->execute([
                            'breed' => $breed,
                        ]);
                        if ($query->rowCount() == 0) {
                            //Jeśli nie to insert
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

    //Pobranie zdjęć
    public function getBreedsImages(): int
    {
        $db = Database::getInstance()->getConnection();
        //Pobranie listy ras
        $query = $db->prepare('SELECT * FROM breed_list');
        $query->execute();
        $breeds = $query->fetchAll();
        $insertsCount = 0;
        //Walidacja czy rasy sa tablica, count > 0
        if (is_array($breeds) && count($breeds) > 0) {
            foreach ($breeds as $breed) {
                if (!empty($breed['sub_breed'])) {
                    //Jeśli sitnieje podrasa pobierz jej zdjęcie
                    $apiQuery = "breed/{$breed['sub_breed']}/images";
                    $breedImages = $this->callApi($apiQuery);
                } elseif (!empty($breed['breed'])) {
                    //Jeśli tylko rasa pobierz zdjęcie rasy
                    $apiQuery = "breed/{$breed['breed']}/images";
                    $breedImages = $this->callApi($apiQuery);
                }
                //Jeśli połączenie z API się udało
                if (isset($breedImages['message']) && $breedImages['status'] == 'success') {
                    foreach ($breedImages['message'] as $breedImage) {
                        //Sprawdź czy zdjęcie istnieje w bazie
                        $db = Database::getInstance()->getConnection();
                        $query = $db->prepare('SELECT * FROM breed_images WHERE image  = :image');
                        $query->execute([
                            ':image' => $breedImage
                        ]);
                        //Jeśli nie insert do bazy
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

    //Pobieranie ciekawostek
    public function getFactsAboutDogs(int $numberOfFacts = 1): array
    {
        $dogFacts = [];
        for ($i = 0; $i < $numberOfFacts; $i++) {
            //Pobranie z endpointa API
            $dogFactResult = $this->callApi('facts');
            //Jeśli połączenie się udało
            if (!empty($dogFactResult['data']) && is_array($dogFactResult)) {
                foreach ($dogFactResult['data'] as $dogFact) {
                    $dogFacts[] = [
                        'id' => $dogFact['id'],
                        'fact' => $dogFact['attributes']['body']
                    ];
                }
            }
        }
        //Zwróć ciekawostki
        return $dogFacts;
    }
}