<?php
    require("../includes/config.php");
    if (empty($_POST["q"])) return "";
    else
    {
        $q=strtolower($_POST["q"]);
        $rows=query("SELECT id,firstname,lastname FROM users");
        $scores=[];
        $results=[];
        foreach ($rows as $row)
        {
            if (!empty($_POST["notme"])&&$_POST["notme"]==1 && $_SESSION["id"]==$row["id"]) continue;
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
    
    
     function containsword($person, $search)
    {
        $token=strtok($search,' ');
        $score=0;
        while ($token!==false)
        {
            $tlength=strlen($token);
            $pronounciation=metaphone($token);
            // If token appears at beginning of department, count each matched character 3 times.
            if (strpos(strtolower($person["firstname"]), $token)===0) 
                $score+=3*$tlength;            
            if (strpos(metaphone(strtolower($person["firstname"])), $pronounciation)===0) 
                $score+=2*$tlength;
            if (strpos(strtolower($person["lastname"]), $token)===0) 
                $score+=3*$tlength;            
            if (strpos(metaphone(strtolower($person["lastname"])), $pronounciation)===0) 
                $score+=2*$tlength;
                
            // if name equals token exactly-- wow!
            if (strtolower($person["firstname"])==$token)
                $score+=5*$tlength;            
            if (metaphone(strtolower($person["firstname"]))==$pronounciation)
                $score+=4*$tlength;
            if (strtolower($person["lastname"])==$token)
                $score+=5*$tlength;            
            if (metaphone(strtolower($person["lastname"]))==$pronounciation)
                $score+=4*$tlength;
            $token=strtok(' ');
        }
        return $score;
    }
?>
