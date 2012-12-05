<?php

    /***********************************************************************
     * functions.php
     *
     * Computer Science 50
     * Problem Set 7
     *
     * Helper functions.
     **********************************************************************/

    require_once("constants.php");


    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
     
    function dump($variable)
    {
        require("../templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }
    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    function getuser($userid)
    {
        $rows=query("SELECT * FROM users WHERE id=?",$userid);
        if (count($rows)!=1) return false;
        $user=$rows[0];
        
        $contents=file_get_contents("../".$user["file"]);
        $user=json_decode($contents,true);
        if (!isset($user["courses"]))
            $user["courses"]=[];
        return $user;
    }
    
    function writeuser($userid,$user)
    {
        $rows=query("SELECT * FROM users WHERE id=?",$userid);
        if (count($rows)!=1) return false;
        if (!file_exists("../".$rows[0]["file"])) 
        {
            $file=fopen("../".$rows[0]["file"],'w');
            fclose($file);
        }
        file_put_contents("../".$rows[0]["file"],json_encode($user));
        return true;
    }
    
        function hasaccess(&$post)
    {
        $privacy=str_getcsv($post["privacy"]);
        if (empty($privacy)) return true;
        switch ($privacy[0]){
            case 0:
                return true;
                break;
            case 1:
                if ($post["poster_id"]!=$_SESSION["id"])
                {
                    $post["poster_firstname"]="Anonymous";
                    $post["poster_lastname"]="";
                }
                return true;
                break;
            case 3:
                $canbeseen=false;
                if ($post["poster_id"]==$_SESSION["id"])
                    $canbeseen=true;
                $post["privateto"]=[];
                foreach (array_slice($privacy,1) as $person)
                {
                    $rows=query("SELECT firstname,lastname FROM users WHERE id=?",$person);
                    if (empty($rows)) continue;
                    array_push($post["privateto"],$rows[0]["firstname"]." ".$rows[0]["lastname"]);
                    if ($_SESSION["id"]==$person)
                    {
                        $canbeseen=true;
                    }
                }
                return $canbeseen;
                break;
            default:
                return true;
        }
    }
    
    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("../templates/header.php");

            // render template
            require("../templates/$template");

            // render footer
            require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }
    
    function createcourseforum($id)
    {
        query("CREATE TABLE postsin".$id."
			(
			post_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			poster_id INT UNSIGNED,
			link INT UNSIGNED,
			poster_firstname VARCHAR(255),
			poster_lastname VARCHAR(255),
			post_title VARCHAR(255),
			tags VARCHAR(255),
			keywords VARCHAR(511),
			post_rating INT,
			file VARCHAR(255),
			privacy VARCHAR(511),
			posttime DATETIME
			)"
		);
        query("CREATE TABLE tagsin".$id."
			(
            tag_type VARCHAR(255),
            tag_name VARCHAR(255) UNIQUE,
            tag_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY 
			)"
		);
    }
    function getusercourses()
    {
        $mycourses=[];
        $courses=$_SESSION["user"]["courses"];
        foreach ($courses as $course)
        {
        if (empty($course)) break;
        $rows=query("SELECT * FROM harvardcourses WHERE id=?",$course);
        if (empty($rows))
        {
            $validcourses=query("SELECT * FROM allharvardcourses WHERE id=?",$course);
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
            $rows=$validcourses; 
        }
        $mycourses[intval($course)]=$rows[0];
        }
        return $mycourses;
    }

?>
