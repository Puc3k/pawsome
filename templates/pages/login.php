<section class="container-fluid form-container">
    <div class="row form-container">
        <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-6">
                <h4>Logowanie</h4>
                <form method="POST">
                    <div class="mb-4 form-group">
                        <label for="exampleInputEmail1" class="form-label">Adres email</label>
                        <input type="text" class="form-control" name="email" value="<?= $params['email'] ?? '' ?>"
                               placeholder="Podaj email">

                    </div>
                    <div class="mb-4 form-group">
                        <label for="exampleInputPassword1" class="form-label">Hasło</label>
                        <input type="password" class="form-control" name="password"
                               value="">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="form-outline w-25 btn btn-primary">Zaloguj</button>
                        <small>Nie masz konta? <a href="/register">Zarejestruj się</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>