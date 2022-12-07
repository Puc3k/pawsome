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
</body>
</html>