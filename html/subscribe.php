<?php
    // configuration
    require("../includes/config.php");
    
    $post_filepath = "../data/posts/".$_POST["course"]."/".$_POST["post"]."/questiondata";
    
    
    if (!file_exists($post_filepath)) 
    {
        $file = fopen($post_filepath,'w');
        fclose($file);
        
        $subscibers = [];
    }
    else
    {
        $contents = file_get_contents($post_filepath);
        $subscribers = json_decode($contents, true);
    }
    if (!empty($subscribers[$_SESSION["id"]])) die("You have already subscribed to this post.");
    
    else $subscribers[$_SESSION["id"]]=1;
        
    file_put_contents($post_filepath, json_encode($subscribers));
    die("You are now subscribed to this post.");
?>
