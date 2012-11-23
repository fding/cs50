<?php
    require_once("../includes/config.php");

    // Find out about the current user and his courses
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    $row=$rows[0];
    $filepath=$row["file"];
    $user=getuser($_SESSION["id"]);
    if ($user===false)
        redirect("start.php");
    else
        $courses=$user["courses"];
    $_SESSION["user"]=$user;
    // Finds information about each course
    $mycourses=[];
    
    $requestmethod="_".$_SERVER["REQUEST_METHOD"];
    $requestmethod=$$requestmethod;
    // Finds information about each course
    foreach ($courses as $course)
    {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    if (!empty($requestmethod["filter"]))
        $filter=$requestmethod["filter"];
    else $filter="";
    // Get the tags, keywords, selectedcourses, and sort method
    // Need error checking to make sure that all queries are valid.
    if (empty($requestmethod["tags"]))
        $selectedtags=[];
    else
        $selectedtags=str_getcsv($requestmethod["tags"]);
        
        
    if (empty($requestmethod["keywords"]))
        $keywords=[];
    else 
        $keywords=$requestmethod["keywords"];
    if (empty($requestmethod["sort"]))
        $sortmethod="post_rating";
    else
        $sortmethod=$requestmethod["sort"];
    if (!empty($filter))
        $sortmethod="score";
        
    $selectedcourses=[];
    $selectedcoursesid=[];
    if (empty($requestmethod["scourses"]))
    {
        $selectedcourses=$mycourses;
        $selectedcoursesid=array_keys($mycourses);
    }
    else
    {
        $selectedcoursesid=str_getcsv($requestmethod["scourses"]);
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
    foreach ($courses as $course)
    {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        $mycourses[intval($course)]=$rows[0];
    }
    
    
    // Find all tags corresponding to selected courses.
    $tags=[];
    foreach ($mycourses as $course)
    {
        $rows=query("SELECT * FROM tagsin".$course["id"]);
        foreach ($rows as $row)
        {
            $tags[$course["id"]][$row["tag_id"]]=$row;
        }
    }
    
    // Create an array to hold all relevant posts,
    // and an array representing the column by which we want to sort.
    $posts=[];
    $$sortmethod=[];
    
    // Retrieves all posts filtered by tags and keywords
    foreach ($selectedcourses as $course)
    {
        $rows=getposts($course,$selectedtags,$keywords,$filter);
        if ($rows===null) continue;
        
        foreach ($rows as $post) 
        {
            $post["course"]=ucwords(strtolower($course["department"]))." ".$course["number"];
            $post["course_id"]=$course["id"];
            array_push($posts,$post);
            array_push($$sortmethod,$post[$sortmethod]);
        }
    }
    // Sort the posts by criterion
    array_multisort($$sortmethod,SORT_DESC,$posts);
    if (count($selectedcoursesid)>1)
        $selectedcoursesid=[0];
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        require_once("../templates/forum_template.php");
        return;
    }
    
    function getposts($course, $tags=[], $keywords=[], $search)
    {
        // Needs to filter through te tags and keywords, and only return the ones with link=0.
        // Furthermore, it also needs to add reference to answers.
        $rows=query("SELECT * FROM postsin".$course["id"]);
        $answer=[];
        foreach ($rows as $row)
        {
            $posttags=str_getcsv($row["tags"]); 
            // If $tags is a subset of $posttags
            if (count(array_diff($tags,array_intersect($tags,$posttags)))==0)
            {
                if (!empty($search))
                {
                    $row["score"]=containsword($row, $search);
                    if ($row["score"]!=0)
                        array_push($answer,$row);
                }
                else
                        array_push($answer,$row);
            }
        }
        return $answer;
    }
    
    // Returns 0 if the post is unrelated to word, a positive integer to represent degree of similarity.
    function containsword($post, $search)
    {
        // Algorithm: A match in the title counts 3 times as much as a match in the actual post, in a keyword 5 times
        // Conected strings of words count one extra.
        // Else, each word counts once.
        // If not every word is present, return 0.
        $token=strtok($search,' ');
        $postcontents=file_get_contents("../data/".$post["file"]);
        $score=$post["post_rating"];
        while ($token!==false)
        {
            $wordfound=false;
            $tlength=strlen($token);
            $addition=3*$tlength*substr_count($post["post_title"], $token);
            $score+=$addition;
            if ($addition!=0)
                $wordfound=true;
            $addition=substr_count($postcontents,$token)*$tlength;
            $score+=$addition;
            if ($addition!=0)
                $wordfound=true;
            /*
                TODO: find keyword in keywords
            */
            if (!$wordfound) return 0;
            $token=strtok(' ');
        }
        return $score;
    }
?>
