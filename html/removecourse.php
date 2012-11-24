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
            $rows=query("SELECT * FROM allharvardcourses WHERE id=?",$_POST["course"]);
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
                if (!empty($_SESSION["user"]) && !empty($_SESSION["user"]["courses"]) ){
                    $i=-1;
                    foreach ($_SESSION["user"]["courses"] as $key=>$entry)
                    {
                        if ($entry==$course["id"])
                        {
                            $i=$key;
                        }
                    }
                    if ($i==-1)
                    {
                        print("You are not registered as taking this course!");
                        die();
                    }
                    unset($_SESSION["user"]["courses"][$i]);
                    $_SESSION["user"]["courses"]=array_values($_SESSION["user"]["courses"]);
                }
                else
                {
                    print("No course selected");
                    die();
                }
                writeuser($_SESSION["id"],$_SESSION["user"]);
            }
        }
        $_SESSION["user"]=getuser($_SESSION["id"]);
        fclose($file);
        print("SUCCESS");
    }
?>
