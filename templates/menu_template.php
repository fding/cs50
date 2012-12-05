<?php if (empty($mycourses)) print("You haven't registered for any courses yet. Add some via the searchbox above!")?>

        
<?php foreach ($mycourses as $course):?>
	<div class="course" id=<?="\"coursebox".$course["id"]."\""?>>
	    <button type="button" class="close removecourse" style="font-size:14px; position:relative; top:-6px;" aria-hidden="true" data-course="<?=$course["id"]?>">&times;
	    </button>
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
		<div class="tagcontainer collapse" id="<?="tagcontainer".$course["id"]?>">
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
				if ($.cookie("collapsed<?=$course["id"]?>"));
					//$(<?="\"#tagcontainer".$course["id"]."\""?> ).collapse('hide');
				else 
					$(<?="\"#tagcontainer".$course["id"]."\""?> ).show();
				
			});
		</script>
	</div>

<? endforeach;?>

<script>
	$(document).ready(function(){
        <?php if(!empty($selectedcourse["id"])):?>
		    $(<?="\"#course".$selectedcourse["id"]."\""?>).addClass("active");
        <?php endif;?>
        <?php if(!empty($selectedtags)):?>
		    $(<?="\"#tag".$selectedtags[0]."-course".$selectedcourse["id"]."\""?>).addClass("active");
        <?php endif;?>
    });
</script>
