<section class="form__section">
    <div class="container form__section-container">
        <h2>Zaloguj się</h2>
        <?php use App\Helpers\Session;

        if (Session::exists('signup-success')) : ?>
            <div class="alert__message success">
                <p>
                    <?= $_SESSION['signup-success'];
                    unset($_SESSION['signup-success']);
                    ?>
                </p>
            </div>
        <?php elseif (Session::exists('signin')) : ?>
            <div class="alert__message error">
                <p>
                    Session::delete('signin')
                    <?= $_SESSION['signin'];
                    unset($_SESSION['signin']);
                    ?>
                </p>
            </div>
        <?php endif ?>

        <form method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Podaj adres email</label>
                <input type="text" class="form-control" name="email" value="<?= $params['email'] ?>" placeholder="Username or Email">

                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">

                <label for="exampleInputPassword1" class="form-label">Hasło</label>
                <input type="password" class="form-control" name="password" value="<?= $params['password'] ?? '' ?>" >
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <small>Don't have account? <a href="register.php">Zarejestruj się</a></small>

    </div>
</section>