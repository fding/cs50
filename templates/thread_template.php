<?php if (!empty($question)):?>
<script>
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"thread-container"]);
</script>
    <input type="hidden" id="thread-info" data-course="<?=$question["course_id"]?>" data-postid="<?=$question["post_id"]?>"/>
    <div class="post-thread-view">
    <?php if($question["poster_id"]==$_SESSION["id"]): ?>
        <button type="button" class="close removepost" data-dismiss="modal" aria-hidden="true"
            data-course="<?=$question["course_id"]?>" data-postid="<?=$question["post_id"]?>">&times;
        </button>
        <button type="button" class="close" data-dismiss="modal" style="position:relative; top:4px;" saria-hidden="true">
            <i class="icon-edit"></i>
        </button>
    <?php endif;?>
        <div class="post-thread-view-tag">
            <?=$question["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$question["course_id"]][$question["tags"]]["tag_name"]?>
        </div>
        <div class="post-thread-view-title">
            <?=$question["post_title"]?>
        </div>
        <div class="post-thread-view-poster">
            <em><?=$question["poster_firstname"]." ".$question["poster_lastname"]?></em> &nbsp;
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
<?php endif?>
