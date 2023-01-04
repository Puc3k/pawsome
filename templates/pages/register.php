<section class="form_section">
    <div class="row">
        <div class="col-md-6 offset-3">
            <p>
            <h4>Rejestracja</h4>
            </p>

            <div class="row">
                <div class="col-md-8 offset-2">
                    <form enctype="multipart/form-data" method="POST">
                        <div asp-validation-summary="ModelOnly" class="text-danger"></div>

                        <div class="mb-4 form-group">
                            <label for="userName" class="form-label">Nazwa użytkownika</label>
                            <input type="text" name="userName" class="form-control"
                                   value="<?= $params['userName'] ?? '' ?>"
                                   placeholder="Podaj nazwę użytkownika">

                        </div>

                        <div class="mb-4 form-group">
                            <label for="exampleInputEmail1" class="form-label">Podaj adres email</label>
                            <input type="text" class="form-control" name="email" value="<?= $params['email'] ?? '' ?>"
                                   placeholder="Podaj email">

                        </div>

                        <div class="mb-4 form-group">
                            <label for="exampleInputPassword1" class="form-label">Hasło</label>
                            <input type="password" name="createPassword" class="form-control" id="exampleInputPassword1"
                                   value="" placeholder="Podaj hasło">
                        </div>

                        <div class="mb-4 form-group">
                            <label for="exampleInputPassword1" class="form-label">Powtórz hasło</label>
                            <input type="password" name="confirmPassword" class="form-control"
                                   id="exampleInputPassword1" value=""
                                   placeholder="Potwierdź hasło">
                        </div>


                        <!--                        <div class="mb-4 form-group">-->
                        <!--                            <label for="avatar" class="form-label">Zdjęcie profilowe</label>-->
                        <!--                            <input type="file" name="avatar" id="avatar">-->
                        <!--                        </div>-->

                        <div class="form-group">

                            <button type="submit" class="form-outline w-25 btn btn-primary">Zarejestruj się</button>
                            <small>Masz już konto? <a href="register.php">Zaloguj</a></small>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>