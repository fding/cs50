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
        if (!empty($_POST["person"]))
        {
            $parts=explode(' ',$_POST["person"]);
            // What if two users have the same first and last names?
            $rows=query("SELECT * FROM users WHERE firstname=? AND lastname=?",$parts[0],$parts[1]);
            if (empty($rows))
            {
                print($_POST["person"]." does not appear to be registered. Do you want to invite him/her?");
                fclose($file);
                die();
            }
            else
            {
                $person=$rows[0];
                if (!empty($_SESSION["user"]) && !empty($_SESSION["user"]["follow"]) ){
                    foreach ($_SESSION["user"]["follow"] as $entry)
                    {
                        if ($entry==$person["id"])
                        {
                            print("You are already following ".$person["firstname"]);
                            die();
                        }
                    }
                }
                else if (!empty($_SESSION["user"]))
                    $_SESSION["user"]["follow"]=[];
                else
                {
                    print("Access Error.");
                    die();
                }
                array_push($_SESSION["user"]["follow"],$person["id"]);
                writeuser($_SESSION["id"],$_SESSION["user"]);
            }
        }
        $_SESSION["user"]=getuser($_SESSION["id"]);
        fclose($file);
        print("SUCCESS");
    }
?>
