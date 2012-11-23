<?php
    // configuration
require("../includes/config.php");
// if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	// Even though we already checked client side for proper form submission, we better properly do so server side also!
	if (empty($_POST["firstname"]))
		print("You must provide a first name.");
	else if (empty($_POST["lastname"]))
		print("You must provide a last name.");
	else if (empty($_POST["password"]))
		print("You must provide a password.");
	else if (empty($_POST["confirmation"]))
		print("You must provide a confirmation password.");
	else if (empty($_POST["email"]))
		print("You must provide a valid Harvard email address");
	else if ($_POST["password"]!=$_POST["confirmation"])
		print("You password and confirmation password do not match.");
	else if (!preg_match('/.+@(.+\.|)harvard\.edu$/',$_POST["email"]))
		print("Your email address does not seem to be a Harvard email address.");
	else{
		$status=query("SELECT * FROM users WHERE email=?",$_POST["email"]);
	
		$confcode=md5(uniqid(rand())); 
	
		if ($status===false)
		{
			print("You seemed to have registered already");
			die();
		}
		else
		{
			$status=query("INSERT INTO unactivated_users (firstname,lastname,email,password,confirmationcode)
			VALUES(?,?,?,?,?)",$_POST["firstname"],$_POST["lastname"],$_POST["email"],crypt($_POST["password"]),$confcode);
			if ($status===false)
			{
				print("You seemed to have registered already. Please check your email for 
				the confirmation code");
				die();
			}
		}
		

		if (sendCode($confcode))
		{
			print("A confirmation email has been sent. Please follow the instructions to complete your registration");
		}
		else
			print("Error sending confirmation email.");
    }
}
else if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (empty($_GET["confirmation"]))
        render("register_form.php", ["title"=>"Registration Error"]);
    else
    {
        $status=query("SELECT * FROM unactivated_users WHERE confirmationcode=?",$_GET["confirmation"]);
        if ($status===false)
			render("register_form.php", ["title"=>"Registration Error"]);
        $status=$status[0];
        query("INSERT INTO users (firstname,lastname,email,password) VALUES(?,?,?,?)",
			$status["firstname"],$status["lastname"],$status["email"],$status["password"]);
        $row=query("SELECT * FROM users WHERE email=?",$status["email"]);
        $_SESSION["id"]=$row[0]["id"];
        $_SESSION["firstname"]=$row[0]["firstname"];
        $file=fopen("../data/users/".$_SESSION["id"]);
        fclose($file);
        redirect('mycourses.php');
    }
}
else
{
        // else render form
    render("register_form.php", ["title" => "Register"]);
}

// Code to send confirmation email.
function sendCode($code)
{
    $message=<<<END_OF_EMAIL
Hello {$_POST["firstname"]}! 

You recently registered for Harvard Discuss. If it was not you who registered for Harvard Disucss, please disregard this email
(but make sure to check us out!)

To complete registration, please
<a href="http://www.harvarddiscuss.net/register.php?confirmation={$code}">click here</a>,
or copy and paste the address (http://www.harvarddiscuss.net/register.php?confirmation={$code}) manually into your broswer.

Thanks!
The Harvard Discuss Team
END_OF_EMAIL;

    $message=wordwrap($message,70);
    
    $to=$_POST["email"];
    $subject="Registration for Harvard Discuss";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $headers .= 'To: <'.$_POST["email"].'>'."\r\n";
    
    $headers .= 'From: <discuss@harvarddiscuss.com>';
    $status=mail($to,$subject,$message,$headers);
    print ($headers);
    print ($message);
    return $status;
}
?>
