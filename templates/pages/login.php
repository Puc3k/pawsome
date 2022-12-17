<section class="form_section">
    <div class="container form_section-conainer">
        <h2>Sign Up</h2>
        <?php if (isset($_SESSION['signup'])) : ?>
            <div class="alert_message error">
                <p>
                    <?= $_SESSION['signup'];
                    unset($_SESSION['signup']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <!-- "multipart/form-data" because we have file upload -->
        <form action="<?= $_SERVER['HTTP_HOST'] ?>signup-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="firstName" value="<?= $params['firstName'] ?>" placeholder="First Name">
            <input type="text" name="lastName" value="<?= $params['lastName'] ?>" placeholder="Last Name">
            <input type="text" name="userName" value="<?= $params['userName'] ?>"  placeholder="Username">
            <input type="email" name="email" value="<?= $params['email'] ?>" placeholder="Email">
            <input type="password" name="createPassword" value="<?= $params['createPassword']?>" placeholder="Create Password">
            <input type="password" name="confirmPassword" value="<?= $params['confirmPassword']?>" placeholder="Confirm Password">
            <div class="form_control">
                <label for="avatar">User Avatar</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn">Sign Up</button>
            <small>Already have an account? <a href="signin.php">Sign In</a></small>
        </form>
    </div>
</section>