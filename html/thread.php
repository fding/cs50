<?php

    // configuration
    require("../includes/config.php"); 
    
    // Find out about the current user and his courses
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    $row=$rows[0];
    $filepath=$row["file"];
    $courses=file("../".$filepath);
    // Finds information about each course
    $mycourses=[];
    foreach ($courses as $course)
    {
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    
    // Get the tags, keywords, selectedcourses, and sort method
    // Need error checking to make sure that all queries are valid.
    if (empty($_GET["thread"]) || empty($_GET["course"]))
        render("error_template.php",["message"=> "No thread selected."]);
        
    if (empty($_GET["sort"]))
        $sortmethod="post_rating";
    else
        $sortmethod=$_GET["sort"];
    
    $thread=$_GET["thread"];
    $threadcourse=$_GET["course"];
    // Find all tags corresponding to selected courses.
    $tags=[];
    foreach ($mycourses as $course)
    {
        $tags[$course["id"]]=query("SELECT * FROM tagsin".$course["id"]);
    }
    
    // Create an array to hold all relevant posts,
    // and an array representing the column by which we want to sort.
    $replies=[];
    $$sortmethod=[];
    $question=[];
    
    // Retrieves all posts filtered by tags and keywords
    $rows=query("SELECT * FROM postsin".$threadcourse);
    if ($rows===null) render("error_template.php",["message"=> "Thread does not exist"]);
    foreach ($rows as $post) 
    {
        $post["course"]=$course["name"];
        $post["course_id"]=$course["id"];
        if ($post["post_id"]==$thread)
        {
            $question=$post;
        }
        else if ($post["link"]==$thread)
        {
            array_push($replies,$post);
            array_push($$sortmethod,$post[$sortmethod]);
        }
    }
    // Sort the posts by criterion
    array_multisort($$sortmethod,SORT_DESC,$replies);
    
    render("index_template.php", ["title" => "Harvard Discuss","tags"=>$tags, 
    "mycourses"=>$mycourses, "question"=>$question, "replies" =>$replies,"todo"=>"thread"]);
    
?>
