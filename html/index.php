<?php

    // configuration
    require("../includes/config.php"); 
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if (isset($_GET["filter"]))
        $filter=$_GET["filter"];
    }
    
    // Find out about the current user and his courses
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
        $sortmethod="post_rating";
    else
        $sortmethod=$_GET["sort"];
        
    $selectedcourses=[];
    $selectedcoursesid=[];
    if (empty($_GET["courses"]))
    {
        $selectedcourses=$mycourses;
        $selectedcoursesid=array_keys($mycourses);
    }
    else
    {
        $selectedcoursesid=str_getcsv($_GET["courses"]);
        foreach ($selectedcoursesid as $id)
        {
            $rows=query("SELECT * FROM harvardcourses WHERE id=?",$id);
            $selectedcourses[$id]=$rows[0];
        }
    }
    
    // Find all tags corresponding to selected courses.
    $tags=[];
    foreach ($mycourses as $course)
    {
        $tags[$course["id"]]=query("SELECT * FROM tagsin".$course["id"]);
    }
    
    // Create an array to hold all relevant posts,
    // and an array representing the column by which we want to sort.
    $posts=[];
    $$sortmethod=[];
    
    // Retrieves all posts filtered by tags and keywords
    foreach ($selectedcourses as $course)
    {
        $rows=getposts($course,$selectedtags,$keywords);
        if ($rows===null) continue;
        
        foreach ($rows as $post) 
        {
            $post["course"]=$course["name"];
            $post["course_id"]=$course["id"];
            array_push($posts,$post);
            array_push($$sortmethod,$post[$sortmethod]);
        }
    }
    // Sort the posts by criterion
    array_multisort($$sortmethod,SORT_DESC,$posts);
    if (count($selectedcoursesid)>1)
        $selectedcoursesid=[0];
    render("index_template.php", ["title" => "Harvard Discuss",
    "selectedcourses"=>$selectedcoursesid,"selectedtags"=>$selectedtags, "tags"=>$tags, 
    "mycourses"=>$mycourses, "posts" =>$posts,"todo"=>"forum"]);
    
    
    function getposts($course, $tags=[], $keywords=[])
    {
        // Needs to filter through te tags and keywords, and only return the ones with link=0.
        // Furthermore, it also needs to add reference to answers.
        $rows=query("SELECT * FROM postsin".$course["id"]);
        $answer=[];
        foreach ($rows as $row)
        {
            $posttags=str_getcsv($row["tags"]); 
            // If $tags is a subset of $posttags
            if (count(array_diff(array_intersect($tags,$posttags),$tags))==0)
                array_push($answer,$row);
        }
        return $answer;
    }
?>
