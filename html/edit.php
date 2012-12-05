<?php
    require("../includes/config.php");
    if ($_SERVER["REQUEST_METHOD"]=="POST")
    {
        if (empty($_POST["courseid"]) || empty($_POST["postid"]))
        {
            die("No thread selected.");
        }
        // TODO Protect against sql injection
        $rows=query("SELECT * FROM postsin".$_POST["courseid"]." WHERE post_id=?",$_POST["postid"]);
        if (count($rows)!=1) die("Thread does not exist");
        if ($rows[0]["poster_id"]!=$_SESSION["id"]) die("Access denied");
        $address = $rows[0]["file"];
        $file = fopen("../data/".$address, "w");
        fwrite($file, $_POST["editpost"]);
        die("SUCCESS");
    }
?>
