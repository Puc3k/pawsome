<main>
    <div class="ranking">
        <div class="container">
            <h1 class="text-center text-light py-4">Twój ranking zdjęć psów</h1>
            <?php foreach ($params['rankingData'] ?? [] as $key => $value): ?>
                <p class="lead">
                    <?="<strong>" . $key + 1 . ".</strong> {$value['fact']}" ?>
                </p>
                <div class="row my-2 glass-effect">
                    <div class="col-lg-1 text-center my-auto">
                        <h1 class="text-light">
                            <?= $key + 1 + "." ?>
                        </h1>
                    </div>
                    <div class="col-lg-2 d-flex justify-content-center align-items-center">
                        <div class="ranking-img dog-image m-3" style="background-image: url(<?="{$value['image']}" ?>);">
                        </div>
                    </div>
                    <div class="col-lg-9 p-2 my-auto">
                        <div class="progress m-2">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: <?="{$value['precent_winning']}" ?>%;"
                                aria-valuenow="<?="{$value['precent_winning']}" ?>" aria-valuemin="0" aria-valuemax="100">
                                <?="{$value['precent_winning']}" ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row my-2 glass-effect">
                <div class="col-lg-1 text-center my-auto">
                    <h1 class="text-light">1.</h1>
                </div>
                <div class="col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="ranking-img dog-image m-3" style="background-image: url(../../images/dog1.jpg);">
                    </div>
                </div>
                <div class="col-lg-9 p-2 my-auto">
                    <div class="progress m-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25"
                            aria-valuemin="0" aria-valuemax="100">25%</div>
                    </div>
                </div>
            </div>

            <div class="row my-2 glass-effect">
                <div class="col-lg-1 text-center my-auto">
                    <h1 class="text-light">2.</h1>
                </div>
                <div class="col-lg-2 d-flex justify-content-center align-items-center">
                    <div class="ranking-img dog-image m-3" style="background-image: url(../../images/dog2.jpg);">
                    </div>
                </div>
                <div class="col-lg-9 p-2 my-auto">
                    <div class="progress m-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 15%;" aria-valuenow="15"
                            aria-valuemin="0" aria-valuemax="100">15%</div>
                    </div>
                </div>
            </div>
            
        </div>
</main>