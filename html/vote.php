<?php
    // configuration
    require("../includes/config.php");
    
    $post_filepath = "../data/posts/".$_POST["postclass"]."/".$_POST["link"]."/votes".$_POST["postid"];
    
    
    if (!file_exists($post_filepath)) 
    {
        $file = fopen($post_filepath,'w');
        fclose($file);
        
        $votes = [];
    }
    else
    {
        $contents = file_get_contents($post_filepath);
        $votes = json_decode($contents, true);
    }
    
    $changed = 0;
    if (empty($votes[$_SESSION["id"]]) || $votes[$_SESSION["id"]] == 0)
    {        
        $changed = 1;
        if ($_POST["type"] == "+ 1")
            $votes[$_SESSION["id"]] = 1;
        else if ($_POST["type"] == "- 1")
            $votes[$_SESSION["id"]] = -1;
    }
    else if (($votes[$_SESSION["id"]] == 1 && $_POST["type"] == "- 1") || ($votes[$_SESSION["id"]] == -1 && $_POST["type"] == "+ 1"))
    {   
        $changed = 1;
        $votes[$_SESSION["id"]] = 0;
    }
    
    if ($changed == 1)
    {
        file_put_contents($post_filepath, json_encode($votes));
        
        // update the vote in the database
        query("UPDATE postsin".$_POST["postclass"]." SET post_rating = post_rating ".$_POST["type"]." WHERE post_id = ".$_POST["postid"]);
    }
    
    print($changed);
?>
