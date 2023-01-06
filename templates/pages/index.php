<main>

    <div class="quiz-index justify-content-md-middle">
        <div class="container d-flex justify-content-center align-items-center flex-column position-absolute top-50 start-50 translate-middle text-center">
            <h1 class="text-light">Najlepsze zdjęcie psa</h1>
            <p class="text-light col-12 col-md-6">Gra z wybieraniem najlepszego zdjęcia psa to zabawna i wciągająca gra,
                w której gracze wybierają jedno z dwóch dostępnych zdjęć psów. Zwyciężca </p>
            <p class="d-flex justify-content-center align-items-center col-5"><a class="btn btn-lg button-color text-center col-12 col-md-6 col-lg-4" href="/quiz">Graj teraz</a></p>
        </div>
    </div>

    <div class="container">
        <div class="row border-bottom">
            <div class="col-12">
                <h1 class="text-center my-4">Losowe ciekawostki o psach</h1>
            </div>
        </div>

        <div class="row border-bottom">
            <div class="col-lg-7">
                <h2 class="fw-normal lh-1 mt-4">Ciekawostki</h2>
                <?php foreach ($params['dogFact'] ?? [] as $key => $value) : ?>
                    <p class="lead">
                        <?= "<strong>" . $key + 1 . ".</strong> {$value['fact']}" ?>
                    </p>
                <?php endforeach; ?>
                <p class="lead">
                    <?php if (empty($params['dogFact'][0]['fact'])) : ?>
                        <?= 'Niestety skończyły nam się ciekawostki :( ' ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-lg-5 dog-image-curiosity d-flex justify-content-center align-items-center">
                <img src="../../images/dog-facts.jpg" alt="Interesting fact about a dog" height="350">
            </div>
        </div>

</main>