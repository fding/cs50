<!DOCTYPE html>

<html>
    <head>
        <link href="css/bootstrap.css" rel="stylesheet"/>
        <link href="css/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet"/>
        <link href="css/posts.css" rel="stylesheet"/>

        <link rel="stylesheet" type="text/css" href="/css/bootstrap-wysihtml5.css"></link>
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css"></link>

        <?php if (isset($title)): ?>
            <title>Harvard Discuss: <?= htmlspecialchars($title) ?></title>
        <?php else: ?>
            <title>Harvard Discuss</title>
        <?php endif ?>

        <script src="js/jquery-1.8.2.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        </script>
        
        <script src="js/wysihtml5-0.3.0.js"></script>
        <script src="js/jquery-1.7.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-wysihtml5.js"></script>
        <script src="js/jquery.cookie.js"></script>
        
        <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          tex2jax: {inlineMath: [['$$','$$'], ['\\(','\\)']], displayMath:[['\\[','\\]']]}
        });
        </script>

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
						<h2 style="position:relative;left:3%;text-shadow: 2px 2px #d3d3d3;font-family:helvetica, sans-serif; color:#A51C30">
						    Harvard Discuss $$\beta$$
						</h2>
					</div>
				<?php
					// Display the log out button if the user is log in, and otherwise, displays the register and log in buttons
				?>
				
				<div id="askModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	                <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		                <h3 id="myModalLabel">Ask</h3>
	                </div>
	                <div class="modal-body">
	                <div id="response">
	                </div>
                        <textarea id="question" placeholder="Post your question here..." style="width: 510px; height: 150px"></textarea>
                        <textarea id="tags" placeholder="Enter tags here..." style="width: 510px; height: 20px"></textarea>
                        <script type="text/javascript">
                        $('#question').wysihtml5();
                        </script>
                    </div>
	                <div class="modal-footer">
		                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		                <button class="btn btn-primary" id="submit">Submit</button>
	                </div>
                </div>
				
				<?php if (isset($_SESSION["id"])):?>
					<div style="margin:5px; position:relative; right:-5%;">
					    <div class="btn-group">
                            <button class="btn">Hi, <?=$_SESSION["firstname"]?>!</button>
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
					            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a tabindex="-1" href="logout.php">Logout</a></li>
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


