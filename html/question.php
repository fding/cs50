<?php
    // configuration
require("../includes/config.php");
// if form was submitted

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Even though we already checked client side for proper form submission, we better properly do so server side also!
	if (empty($_POST["title"]))
		print("You must provide a title.");
	else if (empty($_POST["question"]))
		print("You must provide a question.");
	else if (empty($_POST["type"]))
		print("You must provide a tag type.");
	else if (empty($_POST["psetnum"]))
		print("You must provide a tag number.");
	else if (empty($_POST["course"]))
		print("You must provide a course.");
	else
	{
	    $rows = query("SELECT * FROM harvardcourses WHERE id = ?", $_POST["course"]);
        
        $user = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
        $firstname = $user[0]["firstname"];
        $lastname = $user[0]["lastname"];
        $rawprivacy=str_getcsv($_POST["privacy"]);
        $privacy="";
        if ($rawprivacy[0]!=0 && $rawprivacy[0]!=1 && $rawprivacy[0]!=3)
            $rawprivacy[0]=0;
        $privacy=$rawprivacy[0];
        if ($rawprivacy[0]==3)
        {
            $privacy.=",".$_SESSION["id"];
            if (count($rawprivacy)==1) die("visibleto not set");
            foreach (array_slice($rawprivacy,1) as $person)
            {
                $person=trim($person);
                if (empty($person)) continue;
                $parts=explode(' ',$person);
                // What if two users have the same first and last names?
                $personid=query("SELECT id FROM users WHERE firstname=? AND lastname=?",$parts[0],$parts[1]);
                $privacy.=",".$personid[0]["id"];
            }
        }
        if(count($rows) == 1)
        {
            $courseid = $rows[0]["id"];
            
            // TODO NEED TO PROTECT AGAINST SQL INJECTION
            query("INSERT INTO tagsin".$courseid." (tag_type, tag_name) VALUES (?, ?)", $_POST["type"], $_POST["type"].$_POST["psetnum"]);         
            $tag = query("SELECT * FROM tagsin".$courseid." WHERE tag_name = ?", $_POST["type"].$_POST["psetnum"]);
            $tagid = $tag[0]["tag_id"];
            $date = date('Y-m-d H:i:s');
            query("INSERT INTO postsin".$courseid." (poster_id, link, poster_firstname, poster_lastname, post_title, tags, post_rating, posttime,privacy) VALUES (?,?,?,?,?,?,?,?,?)", $_SESSION["id"], 0, $firstname, $lastname, $_POST["title"], $tagid, 0, $date,$privacy); 
            $post = query("SELECT * FROM postsin".$courseid." WHERE post_id=LAST_INSERT_ID()");
            $postid = $post[0]["post_id"];
            query("UPDATE postsin".$courseid." SET file = ? WHERE post_id = ?", "posts/".$courseid."/".$postid."/main", $postid);
            $address = "../data/posts/" . $courseid . "/" . $postid;
            if (!is_dir("../data/posts/" . $courseid))
                mkdir("../data/posts/" . $courseid);
            mkdir($address);
            $file = fopen($address . "/main", "w");
            fwrite($file, $_POST["question"]);
        }
        else
	    {
	        $rows=query("SELECT name FROM allharvardcourses WHERE id=?",$_POST["course"]);
	        if (empty($rows)) redirect("error.php?code=404");
	        query("INSERT INTO harvardcourses (name) VALUES(?)", $rows[0]["name"]);
	        $id = $_POST["course"];
	        createcourseforum($id);
	        mkdir("../data/posts/" . $id);
	        mkdir("../data/posts/" . $id . "/1");
	        $file = fopen("../data/posts/" . $id . "/1/main", "w");
	        fwrite($file, $_POST["question"]);
	        fclose($file);
	        query("INSERT INTO tagsin".$id."(tag_type, tag_name) VALUES (?, ?)", $_POST["type"], $_POST["type"].$_POST["psetnum"]);
            $date = date('Y-m-d H:i:s');
            query("INSERT INTO postsin".$id." (poster_id, link, poster_firstname, poster_lastname, post_title, tags, post_rating, file, posttime,privacy) VALUES (?,?,?,?,?,?,?,?,?,?)", $_SESSION["id"], 0, $firstname, $lastname, $_POST["title"], 1, 0, "posts/".$id."/1/main", $date,$privacy);             
	        $postid=query("SELECT LAST_INSERT_ID()");
	    }
	    
        if (empty($_SESSION["user"]["read"])) $_SESSION["user"]["read"]=[];
        $_SESSION["user"]["read"][$postid]=1;
        writeuser($_SESSION["id"],$_SESSION["user"]);
	}
}
?>
