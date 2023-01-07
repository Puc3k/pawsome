<main>

    <div class="quiz-index d-flex justify-content-center align-items-center">
        <div
                class="container d-flex justify-content-center align-items-center flex-column text-center">
            <h1 class="text-light">Najlepsze zdjęcie psa</h1>
            <p class="text-light col-12 col-md-6">Gra z wybieraniem najlepszego zdjęcia psa to zabawna i wciągająca
                przygoda, w której gracze wybierają jedno z dwóch dostępnych zdjęć psów. Gracze mogą przeglądać zdjęcia
                psów i głosować na swojego faworyta. Na końcu quizu pokazuje się zdjęcie, które zwyciężyło. </p>
            <p class="d-flex justify-content-center align-items-center col-5"><a
                        class="btn btn-lg button-color text-center col-12 col-md-6 col-lg-4" href="/quiz">Graj teraz</a>
            </p>
        </div>
    </div>

    <div class="container">
        <div class="row border-bottom">
            <div class="col-12">
                <h1 class="text-center my-4">Losowe ciekawostki po angielsku o psach</h1>
            </div>
        </div>

        <div class="row border-bottom">
            <div class="col-12 col-lg-8">
                <?php foreach ($params['dogFact'] ?? [] as $key => $value): ?>
                    <p class="lead">
                        <?= "<strong>" . $key + 1 . ".</strong> {$value['fact']}" ?>
                    </p>
                <?php endforeach; ?>
                <p class="lead">
                    <?php if (empty($params['dogFact'][0]['fact'])): ?>
                        <?= 'Niestety skończyły nam się ciekawostki :( ' ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-12 col-lg-4 dog-image-curiosity d-flex justify-content-center align-items-center">
                <div class="dog-image w-75 h-75" style="background-image: url(/public/images/dog-facts.jpg)"></div>
            </div>
        </div>

</main>