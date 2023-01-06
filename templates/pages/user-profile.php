<section class="container-fluid">
    <div class="row form-container profile-background">
        <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center align-items-center">
            <div class="card mt-3">
                <h5 class="card-header"><?= $params['user']['username'] ?></h5>
                <div class="card-body">
                    <?php if (isset($params['user']['avatar']) && strlen($params['user']['avatar']) > 0) : ?>
                        <img src="../images/user-profile/<?= $params['user']['avatar'] ?>" class="rounded rounded-circle mx-auto d-block p-4 max-w-100"
                             width="360"
                             height="360" alt="User avatar placeholder">
                    <?php else : ?>
                        <img src="../images/dog-avatar.jpg" class="rounded rounded-circle mx-auto d-block p-4 max-w-100"
                             width="360"
                             height="360" alt="User avatar placeholder">
                    <?php endif; ?>
                    <ul>
                        <li>Nazwa użytkownika: <?= $params['user']['username'] ?></li>
                        <li>Email: <?= $params['user']['email'] ?></li>
                        <li>Rola: <?= $params['user']['role'] ?></li>
                    </ul>
                    <a href="/update-images" class="btn button-color-green w-100 mb-3">Aktualizuj zdjęcia psów</a>
                    <a href="/edit-user-profile" class="btn button-color w-100">Edytuj dane</a>
                </div>
            </div>
        </div>
    </div>
</section>