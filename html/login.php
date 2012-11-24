<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["email"]))
        {
            print("You must provide your username.");
            die();
        }
        else if (empty($_POST["password"]))
        {
            print("You must provide your password.");
            die();
        }

        $rows = query("SELECT * FROM users WHERE email = ?", $_POST["email"]);

        // if we found user, check password
        if (count($rows) == 1)
        {
            // first (and only) row
            $row = $rows[0];

            // compare hash of user's input against hash that's in database
            if (crypt($_POST["password"], $row["password"]) == $row["password"])
            {
                // remember that user's now logged in by storing user's ID in session
                $_SESSION["id"] = $row["id"];
                $_SESSION["firstname"]=$row["firstname"];
                $_SESSION["lastname"]=$row["lastname"];
                $_SESSION["user"]=getuser($row["id"]);
                if ($_SESSION["user"]===false || empty ($_SESSION["user"]["courses"])||empty($_SESSION["user"]["courses"]))
                    print("NOCOURSES");
                // redirect to portfolio
                print("SUCCESS");
                die();
            }
        }

        // else apologize
        print("Invalid username and/or password.");
        die();
    }
    else
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }

?>
