<?php
require("../includes/config.php");
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    if (empty($rows))
    {
        print("Error");
        die();
    }
    $user=$rows[0];
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    $row=$rows[0];
    $filepath=$row["file"];
    $courses=file("../".$filepath);
    // Finds information about each course
    $mycourses=[];
    foreach ($courses as $course)
    {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    render("mycourses_template.php", ["user"=>$user, "mycourses"=>$mycourses]);
?>
