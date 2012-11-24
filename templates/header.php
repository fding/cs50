<!DOCTYPE html>

<html>
    <head>
        
        <?php if (isset($title)): ?>
            <title>Harvard Discuss: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Harvard Discuss</title>
        <?php endif ?>
        
        <link href="css/bootstrap.css" rel="stylesheet"/>
        <link href="css/bootstrap-responsive.css" rel="stylesheet"/>

        <link rel="stylesheet" type="text/css" href="/css/bootstrap-wysihtml5.css" /link>
        <link href="css/styles.css" rel="stylesheet"/>
        
        <script src="js/jquery-1.7.2.min.js"></script>

        <script src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        </script>
        
        <script src="js/bootstrap.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/jquery.notification.js"></script>
        
        <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          tex2jax: {inlineMath: [['$$','$$'], ['\\(','\\)']], displayMath:[['\\[','\\]']]}
        });
        </script>
        <script src="js/wysihtml5-0.3.0.js"></script>
        <script src="js/bootstrap-wysihtml5.js"></script>

    </head>

    <body>

        <div class="container-fluid">

            <div id="top">
				<div class="row-fluid">
				<?php
					// Since there are more buttons before user logs in, we can adjust format accordingly.
				?>
				<?php if (isset($_SESSION["id"])):?>
					<div class="span9">
				<?php else:?>
					<div class="span7">
				<?php endif?>
				    <a class="logo" href="index.php">
						<h2 style="position:relative;left:3%;text-shadow: 2px 2px #d3d3d3;font-family:helvetica, sans-serif; color:#A51C30">
						    Harvard Discuss $$\beta$$
						</h2>
					</a>
					</div>
				<?php
					// Display the log out button if the user is log in, and otherwise, displays the register and log in buttons
				?>
				
				<?php if (isset($_SESSION["id"])):?>
					<div style="margin:5px; position:relative; right:-5%;">
					    <div class="btn-group">
                            <button class="btn">Hi, <?=$_SESSION["firstname"]?>!</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
					            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="min-width:130px">
                                <li><a tabindex="-1" href="index.php">Inbox</a></li>
                                <li><a tabindex="-1" href="mycourses.php">Manage courses</a></li>
                                <li><a tabindex="-1" href="logout.php">Logout</a></li>
                                <hr/>
                                <li><a tabindex="-1" href="logout.php">Feedback</a></li>
                            </ul>
                        </div>
					    
				<?php else: ?>
					<div class="span5" style="margin:5px;">
						<form class="form-inline" method="post">
							<input name="loginemail" class="input-medium" placeholder="Email Address" type="text"/>
							<input name="loginpass" class="input-small" placeholder="Password" type="password"/>
							<a href="#" role="button" class="btn" id="login">Log In</a>
							<a href="#myModal" role="button" class="btn" data-toggle="modal">Register</a>
							<div id="loginerror">
							</div>
						</form>
				<?php endif ?>
					</div>
				</div>
            </div>
        </div>
		<div id="middle">
		
	<?php
		// Render the registration form as a modal if not logged in.
	?>
	<?php if (!isset($_SESSION["id"]))
	    require("../templates/registration_template.php");
	    
	?>


