<?php
    require("../includes/config.php");
    require("../templates/header.php");
?>
<h2 style="text-align:center">
    <?php
        if (empty($_GET["code"])) print("Unknown error");
        if ($_GET["code"]==404) print("Oops! We can't find the page you requested. Apologies!");
    ?>
</h2>
<?php
    require("../templates/footer.php");
?>
