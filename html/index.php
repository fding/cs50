<?php

    // configuration
    require("../includes/config.php"); 
    
   
    $courses=$_SESSION["user"]["courses"];
    // Finds information about each course
    $mycourses=[];
    foreach ($courses as $course)
    {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    
    // Get the tags, keywords, selectedcourses, and sort method
    // Need error checking to make sure that all queries are valid.
    if (empty($_GET["tags"]))
        $selectedtags=[];
    else
        $selectedtags=str_getcsv($_GET["tags"]);
        
    if (empty($_GET["keywords"]))
        $keywords=[];
    else 
        $keywords=$_GET["keywords"];
    
    if (empty($_GET["sort"]))
        $sortmethod="helpfulness";
    else
        $sortmethod=$_GET["sort"];
    require('menu.php');
    require('forum.php');
    require('thread.php');
    if (empty($selectedcourses))
        $selectedcourse=[];
    else
        $selectedcourse=$selectedcourses[$selectedcoursesid[0]];
    render("index_template.php", ["title" => "Harvard Discuss",
    "selectedcourse"=>$selectedcourse,"selectedtags"=>$selectedtags, "tags"=>$tags, 
    "mycourses"=>$mycourses, "posts" =>$posts,"question"=>$question, "replies" =>$replies, "sortmethod" => $sortmethod]);
    
?>
