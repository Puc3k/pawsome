<section class="container-fluid form-container d-flex justify-content-center align-items-center flex-column">
    <div class="row w-100">
        <div class="col-12 col-md-6 offset-md-3 d-flex justify-content-center align-items-center">
            <div class="col-12 col-xl-8 glass-effect p-3 p-lg-4">
                <h4>Logowanie</h4>
                <form method="POST">
                    <div class="mb-4 form-group">
                        <label for="email" class="form-label">Adres email</label>
                        <input type="text" class="form-control" name="email" value="<?= $params['email'] ?? '' ?>"
                               placeholder="Podaj email" id="email">

                    </div>
                    <div class="mb-4 form-group">
                        <label for="password" class="form-label">Hasło</label>
                        <input type="password" class="form-control" name="password"
                               value="" placeholder="Wpisz hasło" id="password">
                    </div>

                    <div class="justify-content-center form-group">
                        <button type="submit" class="form-outline col-12 btn button-color">Zaloguj</button>
                        <div class="col-12 text-center mt-1">
                            <small>Nie masz konta? <a href="/register">Zarejestruj się</a></small>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="row w-100 d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-6 mt-5">
            <img src="../images/dog-background.png" class="w-100" alt="Dog login form">
        </div>
    </div>
</section>