<?php
    require("../includes/config.php");
    if (empty($_POST["q"]));
    else
    {
        $q=strtolower($_POST["q"]);
        $rows=query("SELECT * FROM allharvardcourses");
        $scores=[];
        $results=[];
        foreach ($rows as $row)
        {
            $score=containsword($row,$q);
            if ($score != 0)
            {
                array_push($scores,$score);
                array_push($results,$row);
            }
        }
        array_multisort($scores,SORT_DESC,$results);
        if (empty($_POST["n"])){
            print(json_encode($results));
            exit();
        }
        else{
            $n=$_POST["n"];
            if ($n>count($results))
                $n=count($results);
            print json_encode(array_slice($results,0,$n));
            exit();
        }
    }
    
    
     function containsword($course, $search)
    {
        $token=strtok($search,' ');
        $score=0;
        while ($token!==false)
        {
            $tlength=strlen($token);
            // Hardcode a few common abbreviations
            if ($token=="ls" && $course["department"]=="LIFESCI") $score+=14;
            if ($token=="cs" && $course["department"]=="COMPSCI") $score+=14;
            if ($token=="er" && $course["department"]=="ETH-REASON") $score+=14;
            
            // If token appears at beginning of department, count each matched character 3 times.
            if (strpos(strtolower($course["department"]), $token)===0) 
                $score+=3*$tlength;
            // count how many times the token appears in name, and add to score the total matched characters
            $score+=0.6*substr_count(strtolower($course["name"]),$token)*$tlength;
            // If token and course number begin similarly, add each counted digit 3 times
            if (strpos($course["number"], $token)===0) 
                $score+=3*$tlength;
            // Extract numerical parts of course number and token
            $tokencoursenum=0;
            $coursenum=1;
            sscanf($token,"%d", $tokencoursenum);
            sscanf($course["number"], "%d", $coursenum);
            
            // If the course numbers match, great!
            if ($coursenum==$tokencoursenum)
                $score+=6;
            // If the postfixes match (1011a and 1011a), a small bonus!
            if ($course["number"]==$token) 
                $score+=1;
            // if name equals token exactly-- wow!
            if (strtolower($course["name"])==$token)
                $score+=5*$tlength;
            // Similarly for department.
            if ($token== $course["department"]) 
                $score+=6*strlen($token);
            $token=strtok(' ');
        }
        return $score;
    }
?>
