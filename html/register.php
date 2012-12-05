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
	
		if ($status!==false)
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
		else
		{
			print("You seemed to have registered already");
			die();
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
        $status=query("SELECT * FROM unactivated_users WHERE email=?",$_GET["email"]);
        if ($status===false)
			render("register_form.php", ["title"=>"Registration Error"]);
			
        $status=$status[0];
        if ($status["confirmationcode"]!=$_GET["confirmation"])
        	render("register_form.php", ["title"=>"Registration Error"]);
        query("INSERT INTO users (firstname,lastname,email,password) VALUES(?,?,?,?)",
			$status["firstname"],$status["lastname"],$status["email"],$status["password"]);
        $row=query("SELECT * FROM users WHERE email=?",$status["email"]);
        $_SESSION["id"]=$row[0]["id"];
        $_SESSION["firstname"]=$row[0]["firstname"];
        $_SESSION["lastname"]=$row[0]["lastname"];
        $file=fopen("../data/users/".$_SESSION["id"],"w");
        query("UPDATE users SET file=? WHERE id=?","data/users/".$_SESSION["id"],$_SESSION["id"]);
        $_SESSION["user"]=getuser($_SESSION["id"]);
        $_SESSION["user"]["courses"]=[];
        fclose($file);
        redirect('index.php');
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
	$url="http://www.crimsondiscuss.com/register.php?confirmation=".rawurlencode($code)."&email=".rawurlencode($_POST["email"]);
    $message=<<<END_OF_EMAIL
Hello {$_POST["firstname"]}! 
<p>
You recently registered for Crimson Discuss. 
If it was not you who registered for Crimson Discuss, 
please disregard this email
(but make sure to check us out)!
</p>
<p>
To complete registration, please
<a href="{$url}">
click here
</a>,
or copy and paste the address ({$url}) manually into your broswer.
</p>
<p>
So that you will receive updates and alerts in the future, 
we ask that you add us to contacts to avoid future emails being marked as spam. 
You will have the option to set how much email you want to receive once you complete your registration.
</p>
<p>
Thanks!<br/>
The Crimson Discuss Team
</p>
END_OF_EMAIL;
    
    $to="\"{$_POST["firstname"]} {$_POST["lastname"]}\" <".$_POST["email"].">";
    $subject="Registration for Harvard Discuss";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
   $status=mail($to,$subject,$message,$headers);
   return $status;
}
?>
