
<?php foreach ($posts as $post):?>
    <div class="post">
        <div class="posttags">
            <?=$post["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$post["course_id"]][$post["tags"]-1]["tag_name"]?>
        </div>
        <div class="posttitle">
            <a data-course=<?="\"".$post["course_id"]."\""?> data-thread=<?="\"".$post["post_id"]."\""?>> <?=$post["post_title"]?> </a>
        </div>
        <div class="postauthor">
            <em>Asked by </em><?=$post["poster_firstname"]." ".$post["poster_lastname"]?>
        </div>
        <div class="postdate">
            <?=$post["posttime"]?>
        </div>
    </div>
<?php endforeach?>
<script>
    $(document).ready(function(){
        $(".post").click(function(){
            clickedlink=$(this).find("a");
            changethread(clickedlink.data("course"),clickedlink.data("thread"));
            }
        );
    }
    );
    function changethread(thecourse,thethread){
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
			    $(".thread").html(response);
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
