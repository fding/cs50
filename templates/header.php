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

        <link rel="stylesheet" type="text/css" href="/css/bootstrap-wysihtml5.css" />
        <link href="css/styles.css" rel="stylesheet"/>
        
        <script src="js/jquery-1.7.2.min.js"></script>

        <script src="js/jquery.cookie.js"></script>
        <script type="text/x-mathjax-config">
        MathJax.Hub.Config({
          tex2jax: {inlineMath: [['$$','$$'], ['\\(','\\)']], displayMath:[['\\[','\\]']]}
        });
        </script>
        <script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        
        
        <script src="js/bootstrap.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/jquery.notification.js"></script>
        
        <script src="js/wysihtml5-0.3.0.js"></script>
        <script src="js/bootstrap-wysihtml5.js"></script>
    </head>
    <body>
        
		<?php
			// Since there are more buttons before user logs in, we can adjust format accordingly.
		?>
		    
	    <?php if(isset($_SESSION["id"])):?>
            <div id="top">
	    
		    <a class="logo" href="index.php">
		        <img  style="height:40px" src="../img/crimsondiscuss.png"/>
			</a>
	            <div style="position:fixed; left:240px;top:15px;">
                    <a href="#"><i class="icon-bookmark icon-white"></i></a>
                    <a href="#"><i class="icon-comment icon-white"></i></a>
                    <a href="#"><i class="icon-inbox icon-white"></i></a>
	            </div>
	            <div class="input-append" style="position: fixed; left: 305px;top:5px;"> 
                    <input type="text"  search-query" id="searchposts" placeholder="Search for posts"/>
                
                    <div id="sortmethod" class="btn-group" >
                        <button id="viewbutton" class="btn">Helpfulness</button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
	                        <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="min-width:130px" style="z-index:50;">
                            <li><a tabindex="-1" href="">Date</a></li>
                        </ul>
                    </div>
                 </div>

			    <div style="margin:5px; position:fixed; width:300px; top:0%; right:7%; text-align:right;">
			        <div class="btn-group">
                        <button class="btn">Hi, <?=$_SESSION["firstname"]?>!</button>
                        <button class="btn dropdown-toggle" data-toggle="dropdown">
			                <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="min-width:130px;text-align:left;">
                            <li><a tabindex="-1" href="index.php">Inbox</a></li>
                            <li><a tabindex="-1" href="logout.php">Logout</a></li>
                            <hr/>
                            <li><a tabindex="-1" href="#feedbackModal" data-toggle="modal">Feedback</a></li>
                        </ul>
                    </div>
			    </div>
		    </div>
	    <div id="middle" style="background-color:#FFFFFF;">
        <?php require("../templates/feedback_form.php");?>
		<?php else:?>
	    <div id="start_container">
		<?php endif ?>		
