<?php
    // configuration
require("../includes/config.php");
// if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (empty($_POST["feedback"])) print("No feed back");
    else
        submitfeedback($_POST["feedback"]);
}

// Code to send confirmation email.
function submitfeedback($feedback)
{
	$message=$feedback;
    
    $to="fding@college.harvard.edu, sitanchen@college.harvard.edu, peterlu@college.harvard.edu";
    $subject="Feedback from Crimson Discuss";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
    $headers .= 'From: <team@crimsondiscuss.com>';
   $status=mail($to,$subject,$message,$headers);
   return $status;
}
?>
