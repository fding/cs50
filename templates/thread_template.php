<?php if (!empty($question)):?>
<script>
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"thread-container"]);
</script>
<div id="thread-container">
    <div class="post-thread-view">
    <button type="button" style="z-index:-1" class="close removepost" data-dismiss="modal" aria-hidden="true"
    data-course="<?=$question["course_id"]?>" data-postid="<?=$question["post_id"]?>">&times;</button>
        <div class="post-thread-view-tag">
            <?=$question["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$question["course_id"]][$question["tags"]]["tag_name"]?>
        </div>
        <div class="post-thread-view-title">
            <h4><?=$question["post_title"]?></h4>
        </div>
        <div class="post-thread-view-poster">
            <em><?=$question["poster_firstname"]." ".$question["poster_lastname"]?></em>
        </div>
        <div class="post-thread-view-body">
            <?php if(file_exists("../data/".$question["file"])) print(file_get_contents("../data/".$question["file"]));
            else print("Corrupted file");
            ?>
        </div>
        <div class="post-thread-view-vote">
            <a class="upvote" data-postid = "<?= $question["post_id"] ?>" data-postclass = "<?= $question["course_id"] ?>"  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </a>
            <h4 style="text-align:center" id=<?="rating".$question["course_id"]."-".$question["post_id"]?>><?=$question["post_rating"]?></h4>
            <a class="downvote" data-postid = "<?= $question["post_id"] ?>" data-postclass = "<?= $question["course_id"] ?>"  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </div>
    </div>
    <?php foreach($replies as $reply):?>
        <div class="reply-post">
    <button type="button" style="z-index:-1" class="close removepost" data-dismiss="modal" aria-hidden="true"
    data-course="<?=$reply["course_id"]?>" data-postid="<?=$question["post_id"]?>">×</button>
        <div class="reply-post-poster">
            <em><?=$reply["poster_firstname"]." ".$reply["poster_lastname"]?></em>
        </div>
        <div class="reply-post-body">
            <?php if(file_exists("../data/".$reply["file"])) print(file_get_contents("../data/".$reply["file"]));
            else print("Corrupted file");
            ?>
        </div>
        <div class="reply-post-vote">
            <a class="upvote" data-postid = "<?= $reply["post_id"] ?>" data-postclass = "<?= $reply["course_id"] ?>"  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </a>
            <h4 style="text-align:center" id=<?="rating".$reply["course_id"]."-".$reply["post_id"]?>><?=$reply["post_rating"]?></h4>
            <a class="downvote" data-postid = "<?= $reply["post_id"] ?>" data-postclass = "<?= $reply["course_id"] ?>"  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </div>
    </div>
    <?php endforeach;?>
</div>
<div>
    <h4>Reply</h4>
    <textarea id = "reply" name="reply" placeholder="Reply..."></textarea>   
    <button class="btn btn-primary" id="replysubmit">Submit</button>
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
        
    // these two scripts call vote.php to update the helpfulness 
    
    $(document).ready(function(){
        $("#replysubmit").click(function(){
				    var file = question["file"];
				    var courseid = question["course_id"];
				    $.ajax({
					    url:'reply.php',
					    type: 'POST',
					    data:{
						    reply: $("textarea[name=reply]").val(),
                            courseid: <?=$question["course_id"]?>,
                            postid: <?= $question["post_id"] ?>
				    //        file: $("input[name=file]").val()
					    },
					    success: function(response)
					    {
					        $.showmsg("Your reply has been posted.");
						    changethread(<?=$question["course_id"]?>, <?=$question["post_id"]?>,$(".thread").scrollTop());
					    }
				    });
			    return false;
	        });
      });
</script>

<?php endif?>
