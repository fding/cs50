
<?php foreach ($posts as $post):?>
    <div class="post" data-course="<?=$post["course_id"]?>" data-thread="<?=$post["post_id"]?>">
        <div class="posttags">
            <?=$post["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$post["course_id"]][$post["tags"]]["tag_name"]?>
        </div>
        <div class="posttitle">
            <?=$post["post_title"]?>
        </div>
        <div class="postauthor">
            <em>Asked by </em>
            <?php if ($post["poster_id"]==$_SESSION["id"]):?>
             me
            <?php else: ?>
            <a class="persontag" data-title="Follow <?=$post["poster_firstname"]?>" data-fullname="<?=$post["poster_firstname"]." ".$post["poster_lastname"]?>"><?=$post["poster_firstname"]." ".$post["poster_lastname"]?></a>
            <?php endif;?>
            </span>
        </div>
        <div class="postdate">
            <?=$post["posttime"]?>
        </div>
    </div>
<?php endforeach?>
<script>
    function changethread(thecourse,thethread,scrollposition=0){
        $(".thread").html("Loading...");
		$.ajax({
			url:'thread.php',
			type: 'POST',
			data:{
			    thread:thethread,
			    course:thecourse
			},
			success: function(response)
			{
			    $(".thread").html(response).scrollTop(scrollposition);
			    window.history.pushState(null,'',makeurl(thecourse,thethread))
			}
		});
    }
    function makeurl(thecourse,thethread){
        url= "index.php?";
        <?php if (!empty($selectedcoursesid)):?>
            url+="scourses="+<?="\"".$selectedcoursesid[0]."\""?>+"&";
        <?php endif;?>
        <?php if (!empty($selectedtags)):?>
            url+="tags="+<?="\"".$selectedtags[0]."\""?>+"&";
        <?php endif;?>
        <?php if (!empty($sortmethod)):?>
            url+="sort="+<?="\"".$sortmethod."\""?>+"&";
        <?php endif;?>
        url+="course="+thecourse+"&thread="+thethread;
        return url;
    }
</script>
