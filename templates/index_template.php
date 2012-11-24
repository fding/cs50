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

        <?php if (isset($_SESSION["id"])) require("../templates/question_form.php");?>
<script src="js/forumscripts.js">
</script>
<div class="navigation">
    <a href="#askModal" role="button" class="btn btn-large btn-success" id = "btnask" data-toggle="modal" style="margin-top:8px;text-align:center;">Ask a question</a>
    <hr />
    <h4 style="font-family:helvetica, sans-serif; text-align:center;"> Courses</h4>
<?php 
	// Search for courses
?>
        <input style="width:92%;" type="text" data-id="1" class="findcourse" data-provide="typeahead" placeholder="Search for classes">
    
<?php
	// Course-container stores all the courses and associated tags
?>
    <div class="course-container">
<?php foreach ($mycourses as $course):?>
		<div class="course" id=<?="\"coursebox".$course["id"]."\""?>>
	<?php
		// Tag for the course itself
	?>
			<button class="btn btn-small btn-primary discusstag coursetag" type="button" id=<?="\"course".$course["id"]."\""?>>
			  <?=ucwords(strtolower($course["department"]))." ".$course["number"]?>
			</button>
    <?php 
		// Checks to see if the course has any subtags. If it doesn't close the current <div class="course"> and continue
		// to print the next course.
        if (empty($tags[$course["id"]])){
            print("</div>"); 
            continue; 
        }
		// If the course does have subtags, create a collapsible menu containing those tags
    ?>
			<div class="tagcontainer" id=<?="tagcontainer".$course["id"]?> class="collapse">
    <?php foreach ($tags[$course["id"]] as $tag):?>
		<?php
			// Creates the button representing a subtag.
		?>
				<button class="btn btn-info discusstag subtag btn-mini" type="button" id="tag<?=$tag["tag_id"]."-course".$course["id"]?>" >
					<?=$tag["tag_name"]?>
				</button>
    <? endforeach;?>
			</div>
		<?php
			// Add event handlers to the tags and to the collapsible menus
		?>
			<script>
				$(document).ready(function(){
					<?php 
						// Reads in a cookie that stores the current state of each collapsible menu to render it.
					?>
					if ($.cookie("collapsed"+<?=$course["id"]?>))
						$(<?="\"#tagcontainer".$course["id"]."\""?> ).collapse('hide');
					else 
						$(<?="\"#tagcontainer".$course["id"]."\""?> ).show();
					
				});
			</script>
		</div>
<? endforeach;?>
		<script>
			$(document).ready(function(){
		        <?php if(!empty($selectedcoursesid)):?>
				    $(<?="\"#course".$selectedcoursesid[0]."\""?>).addClass("active");
		        <?php endif;?>
		        <?php if(!empty($selectedtags)):?>
				    $(<?="\"#tag".$selectedtags[0]."-course".$selectedcoursesid[0]."\""?>).addClass("active");
		        <?php endif;?>
		    });
		</script>
	</div>
</div>

<?php
    // The control panel: searchbox, and sort method
?>
<div class="forum">
    <div id="panel" style="position: relative; width:100%">
        <input type="text" style="width:47%" search-query" id="searchposts" placeholder="Search for posts"/>
        <div style="position: relative; text-align:right; top:-40px; right: 10px; width:100%">
            Sort by
            <div id="sortmethod" class="btn-group">
                <button id="viewbutton" class="btn">Helpfulness</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
			        <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="min-width:130px">
                    <li><a tabindex="-1" href="">Date</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <?php
        // The gigantic box that contains links to all posts
    ?>
    <div class="post-container">
        <?php 
            if (!empty($posts)) require("../templates/forum_template.php");
            else
            {
                print("No posts to show. But feel free to post questions and invite your friends to answer your questions!");
            }
        ?>
    </div>
</div>
<div class="thread">
    <?php require("../templates/thread_template.php");?>
</div>
