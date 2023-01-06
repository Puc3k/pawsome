<section class="container-fluid form-container">
    <div class="row form-container">
        <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center align-items-center">
            <div class="card my-3">
                <h5 class="card-header"><?= $params['user']['username'] ?></h5>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <?php if (isset($params['user']['avatar']) && strlen($params['user']['avatar']) > 0) : ?>
                            <img src="../images/user-profile/<?= $params['user']['avatar'] ?>"
                                 class="rounded rounded-circle mx-auto d-block p-4 max-w-100"
                                 width="360"
                                 height="360" alt="User avatar">
                        <?php else : ?>
                            <img src="../images/dog-avatar.jpg"
                                 class="rounded rounded-circle mx-auto d-block p-4 max-w-100"
                                 width="360"
                                 height="360" alt="<?= $params['user']['username'] ?> User avatar placeholder">
                        <?php endif; ?>

                        <div class="form-group">
                            <div class="mb-4 form-group">
                                <label for="avatar" class="form-label">Zdjęcie profilowe</label>
                                <input type="file" class="form-control" name="avatar" id="avatar">
                            </div>
                            <label for="userName">Nazwa</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="userName"
                                    name="userName"
                                    value="<?= $params['user']['username'] ?>"
                            />
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Zapisz dane</button>
                            <a href="/user-profile" class="btn btn-secondary">Anuluj</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>