<?php
    require("../includes/config.php");
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    if (empty($rows))
    {
        print("Error");
        die();
    }
    $user=$rows[0];
    $mycourses=[];
    if (empty($_SESSION["user"])|| empty($_SESSION["user"]["courses"]))
    {
        render("mycourses_template.php", ["user"=>$user, "mycourses"=>[],"followedpeople"=>[]]);
        die();
    }
    foreach ($_SESSION["user"]["courses"] as $course)
    {
        if (empty($course)) break;
        if ($course=="\n") break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    
    $followedpeople=[];
    if (!empty($_SESSION["user"]["follow"])){
        foreach ($_SESSION["user"]["follow"] as $person){
            $rows=query("SELECT id,firstname,lastname FROM users WHERE id=?",$person);
            array_push($followedpeople,$rows[0]);
        }
    }
    render("mycourses_template.php", ["user"=>$user, "mycourses"=>$mycourses, "followedpeople"=>$followedpeople]);
?>
