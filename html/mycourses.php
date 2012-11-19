<?php
require("../includes/config.php");
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    if (empty($rows))
    {
        print("Error");
        die();
    }
    $user=$rows[0];
    render("mycourses_template.php", ["user"=>$user]);
?>
