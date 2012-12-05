<?php
    // configuration
require("../includes/config.php");
// if form was submitted

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
// TODO NEED TO ENSURE ATOMICITY IN RETRIEVING MOST RECENT REPLY ID
    $firstname = $_SESSION["firstname"];
    $lastname = $_SESSION["lastname"];
    // TODO Need to verify $_POST["courseid"] is in fact an integer.
    $post = query("SELECT * FROM postsin".$_POST["courseid"]." WHERE post_id = ?", $_POST["postid"]);
    if (count($post)!=1)
    {
        print("Invalid thread.");
        die();
    }
    $post=$post[0];
    $date = date('Y-m-d H:i:s');
    query("INSERT INTO postsin".$_POST["courseid"]." (poster_id, link, poster_firstname, poster_lastname, post_title, tags, post_rating,posttime,privacy) VALUES (?,?,?,?,?,?,?,?,?)", 
        $_SESSION["id"], $post["post_id"], $firstname, $lastname, $post["post_title"], $post["tags"], 0,$date,$post["privacy"]);    
    $info = query("SELECT * FROM postsin".$_POST["courseid"]." WHERE post_id=LAST_INSERT_ID()");
    $replyid = $info[0]["post_id"];
    $address = "../data/posts/" . $_POST["courseid"] . "/" . $post["post_id"] ."/reply" . $replyid;
    query("UPDATE postsin".$_POST["courseid"]." SET file = ? WHERE post_id = ?", "posts/" . $_POST["courseid"] . "/" . $post["post_id"] ."/reply" . $replyid, $replyid);
    $file = fopen($address, "w");
    fwrite($file, $_POST["reply"]);
    fclose($file);
    
    if (empty($_SESSION["user"]["read"])) $_SESSION["user"]["read"]=[];
        $_SESSION["user"]["read"][$replyid]=1;
    writeuser($_SESSION["id"],$_SESSION["user"]);
	query("INSERT INTO updatelog (post_id, course_id,link, type) VALUES (?,?,?,?)",$replyid,$_POST["courseid"],$post["post_id"],"reply");
    print("SUCCESS");
    die();
}
?>
