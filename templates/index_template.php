<?php
	/*
		Available variables:
		$mycourses: a list of courses, with attributes "id", "name", poster_firstname, poster_lastname, etc.
		$tags: an associative array indexed by course id. $tags[id] is an array of all tags associated with a course, indexed by
			the id of the tag minus one. Each element of $tags[id] has values "tag_name" and "tag_id".
	*/
?>
<div class="metacontainer">
	<?php
		// Navigation Panel
	?>
	<br />
    <div class="navigation">
        <a href="#askModal" role="button" class="btn btn-large btn-success" id = "btnask" data-toggle="modal" style="margin-top:8px;text-align:center;">Ask a question</a>
        <hr />
        <h4 style="font-family:helvetica, sans-serif; text-align:center;"> Courses</h4>
    <?php 
		// Search for courses
	?>
        <form class="form-search">
            <input type="text" data-provide="typeahead" placeholder="Search for classes">
        </form>
	<?php
		// Course-container stores all the courses and associated tags
	?>
        <div class="course-container">
    <?php foreach ($mycourses as $course):?>
			<div class="course" id=<?="\"coursebox".$course["id"]."\""?>>
		<?php
			// Tag for the course itself
		?>
				<button class="btn btn-small btn-primary discusstag" type="button" id=<?="\"course".$course["id"]."\""?>>
				  <?=$course["name"]?>
				</button>
        <?php 
			// Checks to see if the course has any subtags. If it doesn't close the current <div class="course"> and continue
			// to print the next course.
            if (empty($tags[$course["id"]]))
            {
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
					<button class="btn btn-info discusstag btn-mini" type="button" id=<?="\"tag".$tag["tag_id"]."\""?> >
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
							// We first hide the menu via css to prevent the user from seing the animation while menu is collapsing,
							// and we display it again so that if the menu is again expanded, it is actually visible.
						?>
						if ($.cookie("collapsed"+<?=$course["id"]?>))
						{
							$("#elem").css('visibility', 'invisible');
							$(<?="\"#tagcontainer".$course["id"]."\""?> ).collapse('hide');
							$(<?="\"#tagcontainer".$course["id"]."\""?> ).css('visibility', 'visible');
						}
						else 
							$(<?="\"#tagcontainer".$course["id"]."\""?> ).show();
						
						<?php
							// Event handler for the course tags
						?>
						$(<?="\"#course".$course["id"]."\""?>).click(function()
						{
							id=$(this).attr("id").substring(6);
							submit(id,$(this).hasClass("active"));
						}
						);
						
						<?php
							// Event handler for the collapsible menus
						?>
						$(<?="\"#coursebox".$course["id"]."\""?>).click(function(e)
						{
							if($(e.target).is('button')){
								e.preventDefault();
								return;
							}
							$(<?="\"#tagcontainer".$course["id"]."\""?> ).collapse('toggle');
						}
						);
						
						<?php
							// Stores the collapsed states so that if the user reloads the page, menus he closed won't reopen.
						?>
						$(<?="\"#coursebox".$course["id"]."\""?>).on('hidden', function () {
							$.cookie("collapsed"+<?=$course["id"]?>,true);
						});
						$(<?="\"#coursebox".$course["id"]."\""?>).on('shown', function () {
							$.cookie("collapsed"+<?=$course["id"]?>,null);
						});
						
					});
				</script>
			</div>
	<? endforeach;?>
			<script>
			<?php if(!empty($selectedcourses)):?>
				$(document).ready(function(){
					$(<?="\"#course".$selectedcourses[0]."\""?>).toggleClass("active");
				});
			<?php endif;?>
			<?php
				// Submit changes in selected courses
			?>
				function submit(id,activated){
					if (activated)
						newlocation="index.php"; 
					else
						newlocation="index.php?courses="+id;
					window.location.replace(newlocation);
			   }
			</script>
		</div>
	</div>
    <?php
        if ($todo=="forum")
            require("../templates/forum_template.php");
        if ($todo=="thread")
            require("../templates/thread_template.php");
    ?>
</div>
