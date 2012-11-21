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
	    $rows = query("SELECT * FROM harvardcourses WHERE name = ?", $_POST["course"]);

        if(count($rows) == 1)
        {
            $courseid = $rows[0]["id"];
            $user = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
            $firstname = $user[0]["firstname"];
            $lastname = $user[0]["lastname"];
            query("INSERT INTO tagsin".$courseid." (tag_type, tag_name) VALUES (?, ?)", $_POST["type"], $_POST["type"].$_POST["psetnum"]);
            $tag = query("SELECT * FROM tagsin".$courseid." WHERE tag_name = ?", $_POST["type"].$_POST["psetnum"]);
            $tagid = $tag[0]["tag_id"];  
            query("INSERT INTO postsin".$courseid." (poster_id, link, poster_firstname, poster_lastname, post_title, tags, post_rating, file) VALUES (?,?,?,?,?,?,?,?)", $_SESSION["id"], 0, $firstname, $lastname, $_POST["title"], $tagid, 0, "posts/".$courseid."/".$tagid."/main"); 
            $address = "../data/posts/" . $courseid . "/" . $tagid;
            mkdir($address);
            $file = fopen($address . "/main", "w");
            fwrite($file, $_POST["question"]);
        }
        else
	    {
	        query("INSERT INTO harvardcourses (name) VALUES(?)", $_POST["course"]);
	        $rows = query("SELECT id FROM harvardcourses WHERE name = ?", $_POST["course"]);
	        $id = $rows[0]["id"];
	        createcourseforum($id);
//	        mkdir("../data/" . $id . "/");
	    }
	}
}
?>
