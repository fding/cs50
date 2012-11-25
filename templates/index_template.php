<?php
	/*
		Available variables:
		$mycourses: a list of courses, with attributes "id", "name", poster_firstname, poster_lastname, etc.
		$tags: an associative array indexed by course id. $tags[id] is an array of all tags associated with a course, indexed by
			the id of the tag minus one. Each element of $tags[id] has values "tag_name" and "tag_id".
	    NOTES: to reduce the size of the html file, we might want to factor out some of the machine-produced javascript
	    into common functions.
	    
	*/
?>
<?php
	// Navigation Panel
?>

        <?php require("../templates/question_form.php");?>
<script src="js/forumscripts.js">
</script>


<div class="navigation">
    <a href="#askModal" role="button" class="btn btn-large btn-success" id = "btnask" data-toggle="modal" style="margin-top:8px;text-align:center;">Ask a question</a>
    <hr />
    <h4 style="font-family:helvetica, sans-serif; text-align:center;"> Courses</h4>
<?php 
	// Search for courses
?>
    <form>
        <div class="input-append" style="padding:4px; position:relative;">
            <input type="text" data-id="1" id="newclass" class="findcourse" data-provide="typeahead" 
                placeholder="Add new classes" style="width:95%; border-radius:4px;" />
            <button class="btn addcoursebutton" type="button">+</button>
        </div>
    </form>
<?php
	// Course-container stores all the courses and associated tags
?>
    <div class="course-container">
        <?php require("menu_template.php"); ?>
	</div>
</div>


<?php
    // The control panel: searchbox, and sort method
?>
<div class="forum">
    <div class="post-container">
        <?php 
             require("../templates/forum_template.php");
        ?>
    </div>
</div>
<div class="thread">
    <div id="thread-container">
    <?php require("../templates/thread_template.php");?>
    </div>
    
    <?php // TODO: check if an hidden element is set instead of using php. ?>
    <?php if (!empty($question)):?>
    <div id="reply-collapse"><h5>Reply</h5>
    </div>
    <div id="reply-area" style="height:0%; overflow:hidden;">
        <textarea id = "reply" name="reply" style="width:90%;" placeholder="Reply..."></textarea>   
        <button class="btn btn-primary" id="replysubmit">Submit</button>
    </div>
</div>
<script>
    $('#reply').wysihtml5({
	"font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
	"emphasis": true, //Italics, bold, etc. Default true
	"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
	"html": false, //Button which allows you to edit the generated HTML. Default false
	"link": true, //Button to insert a link. Default true
	"image": true, //Button to insert an image. Default true,
	"color": false //Button to change color of font  
    });
</script>
<?php endif;?>
</div>
