<?php
    require("../includes/config.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["person"])) die("No person specified");
        if (empty($_POST["course"])) die("No course specified");
        if (empty($_POST["post"])) die("No post specified");
        
        $parts=explode(' ',$_POST["person"]);
            // What if two users have the same first and last names?
        $person=query("SELECT email, firstname,lastname FROM users WHERE firstname=? AND lastname=?",$parts[0],$parts[1]);
        $post=query("SELECT * FROM postsin".$_POST["course"]." WHERE post_id=?",$_POST["post"]);
        if (empty($post)) die("Invalid post");
        $post=$post[0];
        if (empty($person)) die("Person not registered");
        
        $person=$person[0];
        $url="www.crimsondiscuss.com/index.php?course=".$_POST["course"]."&thread=".$_POST["post"];
        $message=<<<END_OF_EMAIL
Hi {$person["firstname"]}! 
<p>
{$_SESSION["firstname"]} invited you to answer a question 
("{$post["post_title"]}") on Crimson Discuss!
Help {$_SESSION["firstname"]} and your other classmates 
by <a href="$url">responding</a> 
to this question.
</p>
<p>
Thanks!<br/>
The Crimson Discuss Team
</p>
END_OF_EMAIL;
            
        $to="\"{$person["firstname"]} {$person["lastname"]}\" <".$person["email"].">";
        $subject="A question for you on Crimson Discuss";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
       $status=mail($to,$subject,$message,$headers);
       die("");
    }
?>
