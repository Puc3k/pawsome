<section class="form_section min-vh-100">
    <div class="row">
        <div class="col-md-6 offset-3">
            <div class="row">
                <div class="col-md-8 offset-2">
                    <h4>Logowanie</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-2">
                    <form method="POST">
                        <div asp-validation-summary="ModelOnly" class="text-danger"></div>

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
    </div>
</section>