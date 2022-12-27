<html lang="en">
    <!-- header -->
    <?php require_once('header.php') ?>
    <!-- end of header -->
<body>
    <nav>
        <?php require_once('navigation.php') ?>
    </nav>
    <div class="container">
        <?php require_once("../templates/pages/$page.php"); ?>
    </div>
    <!-- footer -->
        <?php require_once('footer.php') ?>
    <!-- end of footer -->
    <script src="/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>