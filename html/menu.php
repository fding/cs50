<?php
    require_once("../includes/config.php");
    $courses=$_SESSION["user"]["courses"];
    // Finds information about each course
    $mycourses=[];
    $tags=[];
    foreach ($courses as $course)
    {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    foreach ($mycourses as $course)
    {
        $tags[$course["id"]]=query("SELECT * FROM tagsin".$course["id"]);
    }
    // Get the tags, keywords, selectedcourses, and sort method
    // Need error checking to make sure that all queries are valid.
    $requestmethod="_".$_SERVER["REQUEST_METHOD"];
    $requestmethod=$$requestmethod;
    $selectedcoursesid=[];
    if (empty($requestmethod["scourses"]))
    {
        $selectedcoursesid=array_keys($mycourses);
    }
    else
    {
        $selectedcoursesid=str_getcsv($requestmethod["scourses"]);
    }
    
    
    if (empty($requestmethod["tags"]))
        $selectedtags=[];
    else
        $selectedtags=str_getcsv($requestmethod["tags"]);
        
    if ($_SERVER["REQUEST_METHOD"]=="POST")
    {
        require_once("../templates/menu_template.php");
        return; 
    }
?>
