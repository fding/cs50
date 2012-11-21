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
	    $rows = query("SELECT * FROM harvardcourses WHERE name = ?", course);
        if(count($rows) == 1)
        {
        
        }
        else
	    {
	        query("INSERT INTO 
	        createcourseforum(
	    }
	}
?>
