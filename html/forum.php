<?php
    // What should happen if the user enters address for a forum that doesn't exist yet?
    
    require_once("../includes/config.php");
    $requestmethod="_".$_SERVER["REQUEST_METHOD"];
    $requestmethod=$$requestmethod;

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
        $sortmethod="helpfulness";
    else
        $sortmethod=$requestmethod["sort"];
    if (!empty($filter))
        $sortmethod="score";
        
    $selectedcourses=[];
    $selectedcoursesid=[];
    if (empty($requestmethod["scourses"]))
    {
        $selectedcourses=getusercourses();
        $selectedcoursesid=array_keys($selectedcourses);
    }
    else
    {
        $selectedcoursesid=str_getcsv($requestmethod["scourses"]);
        foreach ($selectedcoursesid as $id)
        {
            $rows=query("SELECT * FROM harvardcourses WHERE id=?",$id);
            if (empty($rows))
            {
                $validcourses=query("SELECT * FROM allharvardcourses WHERE id=?",$id);
                if (empty($validcourses))
                {
                    redirect("error.php?code=404");
                    return;
                }
                query("INSERT INTO harvardcourses (id, name, department, cat_num, term, number) VALUES
                        (?,?,?,?,?,?)",$validcourses[0]["id"], $validcourses[0]["name"], $validcourses[0]["department"], 
                        $validcourses[0]["cat_num"],$validcourses[0]["term"], $validcourses[0]["number"]);
                createcourseforum($validcourses[0]["id"]);
                mkdir("../data/posts/" . $validcourses[0]["id"]);
                $selectedcourses[$id]=$validcourses[0];
            }
            else $selectedcourses[$id]=$rows[0];
        }
    }
    // Find all tags corresponding to selected courses.
    if (empty($tags)) $tags=[];
    
    // Create an array to hold all relevant posts,
    // and an array representing the column by which we want to sort.
    $posts=[];
    $$sortmethod=[];
    
    // Find all tags corresponding to selected courses.
    foreach ($selectedcourses as $course)
    {
        $rows=query("SELECT * FROM tagsin".$course["id"]);
        foreach ($rows as $row)
        {
            $tags[$course["id"]][$row["tag_id"]]=$row;
        }
    }
    
    // Create an array to hold all relevant posts,
    // and an array representing the column by which we want to sort.
    $allposts=[];
    $$sortmethod=[];
    
    // Retrieves all posts filtered by tags and keywords
    foreach ($selectedcourses as $course)
    {
        $posts=[];
        $rows=getposts($course,$selectedtags,$keywords,$filter);
        if ($rows===null) continue;
        
        foreach ($rows as $post) 
        {
            $post["course"]=ucwords(strtolower($course["department"]))." ".$course["number"];
            $post["course_id"]=$course["id"];
            if (empty($post["score"])) $post["score"]=0;
            if ($post["link"]!=0 && $post["link"] != $post["post_id"])
            {
                if (empty($posts[$post["link"]]))
                {
                    $rows=query("SELECT * FROM postsin".$course["id"]." WHERE post_id=?",$post["link"]);
                    if (count($rows)==1)
                    {
                        if (!hasaccess($rows[0])) continue;
                        $posts[$post["link"]]=$rows[0];
                        $posts[$post["link"]]["replies"]=[];
                        $posts[$post["link"]]["helpfulness"]=4*$rows[0]["post_rating"];
                        $posts[$post["link"]]["lastedit"]=$post["posttime"];
                        $posts[$post["link"]]["score"]=0;
                        $posts[$post["link"]]["read"]=false;
                        if (!empty($_SESSION["user"]["read"]) && !empty($_SESSION["user"]["read"][$post["link"]]))
                        {
                            $posts[$post["link"]]["read"]=true;
                        }
                        $posts[$post["link"]]["course"]=$post["course"];
                        $posts[$post["link"]]["course_id"]=$post["course_id"];
                    }
                    else continue;
                }
                array_push($posts[$post["link"]]["replies"],$post);
                $posts[$post["link"]]["helpfulness"]+=$post["post_rating"];
                $posts[$post["link"]]["score"]+=$post["score"];
                if (strtotime($posts[$post["link"]]["lastedit"])<strtotime($post["posttime"]))
                    $posts[$post["link"]]["lastedit"]=$post["posttime"];
                if (!empty($_SESSION["user"]["read"]) && empty($_SESSION["user"]["read"][$post["post_id"]]))
                    $posts[$post["link"]]["read"]=false;
            }
            else {
                $post["replies"]=[];
                $post["helpfulness"]=4*$post["post_rating"];
                $post["lastedit"]=$post["posttime"];
                $post["read"]=false;
                $post["score"]=0;
                if (!empty($_SESSION["user"]["read"]) && !empty($_SESSION["user"]["read"][$post["post_id"]]))
                {
                    $post["read"]=true;
                }
                $posts[$post["post_id"]]=$post;
            }
        }
        $allposts=array_merge($allposts,array_values($posts));
    }
    $posts=$allposts;
    
    foreach ($posts as $post)
    {
        $post["helpfulness"]+=1;
        if ($post["read"]) $post["helpfulness"]/=4;
        $post["helpfulness"]/=((time()-strtotime($post["lastedit"]))/86400.0+1);
        array_push($$sortmethod,$post[$sortmethod]);
    }
    
    
    // Sort the posts by criterion
    array_multisort($$sortmethod,SORT_DESC,$posts);
    if (count($selectedcoursesid)>1)
    {
        $selectedcourses=[];
        $selectedcoursesid=[];
    }
    else if (empty($selectedcoursesid))
    {
        $selectedcourses=[];
        $selectedcoursesid=[];
    }
    else
        $selectedcourses[$selectedcoursesid[0]]["course"]=ucwords(strtolower($selectedcourses[$selectedcoursesid[0]]["department"]))." ".$selectedcourses[$selectedcoursesid[0]]["number"];
    
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
            if (!hasaccess($row)) continue;
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
        $postcontents=strip_tags(file_get_contents("../data/".$post["file"]));
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
