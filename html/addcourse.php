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
        $file=fopen("../".$user["file"],'a');
        if (!empty($_POST["course"]))
        {
            $parts=explode(' ',$_POST["course"]);
            $title=implode(' ', array_slice($parts,1,count($parts)-1));
            // Need code to check whether course is valid
            $rows=query("SELECT * FROM allharvardcourses WHERE name=?",$title);
            if (empty($rows))
            {
                print($title." does not appear to be a valid course");
                fclose($file);
                die();
            }
            else
            {
                $course=$rows[0];
                $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course["id"]);
                if (empty($rows))
                {
                    query("INSERT INTO harvardcourses (id, name, department, cat_num, term, number) VALUES
                    (?,?,?,?,?,?)",$course["id"], $course["name"], $course["department"], $course["cat_num"],
                    $course["term"], $course["number"]);
                    createcourseforum($course["id"]);
	                mkdir("../data/posts/" . $course["id"]);
                }
                if (!empty($_SESSION["user"]) && !empty($_SESSION["user"]["courses"]) ){
                    foreach ($_SESSION["user"]["courses"] as $entry)
                    {
                        if ($entry==$course["id"])
                        {
                            print("Course already added.");
                            die();
                        }
                    }
                }
                else
                    $_SESSION["user"]["courses"]=[];
                array_push($_SESSION["user"]["courses"],$course["id"]);
                writeuser($_SESSION["id"],$_SESSION["user"]);
            }
        }
        $_SESSION["user"]=getuser($_SESSION["id"]);
        fclose($file);
        print("SUCCESS");
    }
?>
