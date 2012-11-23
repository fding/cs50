<?php
    // configuration
    require("../includes/config.php");
    
    // update the vote in the database
    query("UPDATE postsin".$_POST["postclass"]." SET post_rating = post_rating ".$_POST["type"]." WHERE post_id = ".$_POST["postid"]);
?>
