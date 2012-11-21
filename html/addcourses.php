<?php
    require("../includes/config.php");
    $rows=query("SELECT * FROM users WHERE id=?",$_SESSION["id"]);
    $user=$rows[0];
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {   
        if (empty($user["file"]))
        {
            $user["file"]="data/users/".$user["id"];
            query("UPDATE users SET file=? WHERE ID=?",$user["file"],$user["id"]);
        }
        $i=1;
        $file=fopen("../".$user["file"],'a');
        while (!empty($_POST["course$i"]))
        {
            // Need code to check whether course is valid
            $rows=query("SELECT * FROM harvardcourses WHERE name=?",$_POST["course$i"]);
            if (empty($rows))
            {
                query("INSERT INTO harvardcourses (name) VALUES (?)",$_POST["course$i"]);
                $rows=query("SELECT * FROM harvardcourses WHERE name=?",$_POST["course$i"]);
            }
            $course=$rows[0];
            fwrite($file,$course["id"]."\n");
            $i+=1;
        }
        fclose($file);
        redirect("/");
    }
?>
