<?php
    require_once("../includes/functions.php");
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
        $threadcourse=$_GET["course"];
    }
    
    if (!isset($tags))
    {
        $tags[$threadcourse]=query("SELECT * FROM tagsin".$threadcourse);
    }
    $rows=query("SELECT * FROM harvardcourses WHERE id=?",$threadcourse);
    $course=$rows[0];
    // Retrieves all posts filtered by tags and keywords
    $rows=query("SELECT * FROM postsin".$threadcourse);
    if ($rows===null) render("error_template.php",["message"=> "Thread does not exist"]);
    $replies=[];
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
    if (!empty($replies))array_multisort($$sortmethod,SORT_DESC,$replies);
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require("../templates/thread_template.php");
        return;
    }
    
?>
