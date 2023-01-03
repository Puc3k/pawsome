<main>

    <div class="quiz-index justify-content-md-middle">
        <div class="container d-flex justify-content-center align-items-center flex-column position-absolute top-50 start-50 translate-middle text-center">
            <h1 class="text-light">Najlepsze zdjęcie psa</h1>
            <p class="text-light col-12 col-md-6">Gra z wybieraniem najlepszego zdjęcia psa to zabawna i wciągająca gra,
                w której gracze wybierają jedno z dwóch dostępnych zdjęć psów. Zwyciężca </p>
            <p><a class="btn btn-lg btn-light" href="/quiz">Graj teraz</a></p>
        </div>
    </div>

    <div class="container">
        <div class="row border-bottom">
            <div class="col-12">
                <h1 class="text-center my-4">Ciekawostka na dziś </h1>
            </div>
        </div>

        <div class="row border-bottom">
            <div class="col-lg-7">
                <h2 class="fw-normal lh-1 mt-4">Ciekawostka</h2>
                <p class="lead">
                    <?= $params['dogFact'][0]['fact'] ?? 'Niestety skończyły nam się ciekawostki :( ' ?>
                </p>
            </div>
            <div class="col-lg-5 dog-image-curiosity d-flex justify-content-center align-items-center">
                <img src="../../images/ciekawostka.jpg" alt="Interesting fact about a dog" width="350" height="350">
            </div>
        </div>

</main>