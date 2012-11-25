<?php
    require_once("../includes/config.php");
    $sortmethod="post_rating";
    $question=[];
    $replies=[];
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["thread"])){
            $question=[];
            $replies=[];
            return;
        }
        $thread=$_POST["thread"];
        if (empty($_POST["course"])){
            $question=[];
            $replies=[];
            return;
        }
        if (!empty($_POST["sort"])){
            $sortmethod=$_POST["sort"];
        }
        $threadcourse=$_POST["course"];
    }
    elseif($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (empty($_GET["thread"])){
            $question=[];
            $replies=[];
            return;
        }
        $thread=$_GET["thread"];
        if (empty($_GET["course"])){
            $question=[];
            $replies=[];
            return;
        }
        if (!empty($_GET["sort"])){
            $sortmethod=$_GET["sort"];
        }
        $threadcourse=$_GET["course"];
    }
    
    if (!isset($tags))
    {
        $rows=query("SELECT * FROM tagsin".$threadcourse);
        foreach ($rows as $row)
        {
            $tags[$threadcourse][$row["tag_id"]]=$row;
        }
    }
    
    $rows=query("SELECT * FROM harvardcourses WHERE id=?",$threadcourse);
    if (count($rows)!=1)
    {
        print ("Thread does not exist");
        die();
    }
    $course=$rows[0];
    // Retrieves all posts filtered by tags and keywords
    $rows=query("SELECT * FROM postsin".$threadcourse." WHERE post_id=? OR link=?",$thread,$thread);
    if ($rows===null) render("error_template.php",["message"=> "Thread does not exist"]);
    $replies=[];
    $$sortmethod=[];
    foreach ($rows as $post) 
    {
        $post["course"]=ucwords(strtolower($course["department"]))." ".$course["number"];
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
    if (!empty($replies))array_multisort($$sortmethod,SORT_DESC,$replies);
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require("../templates/thread_template.php");
        return;
    }
    
?>
