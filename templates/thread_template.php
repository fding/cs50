<?php if (!empty($question)):?>
<script>
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"thread-container"]);
</script>
<div id="thread-container">
    <div class="post-thread-view">
        <div class="post-thread-view-tag">
            <?=$question["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$question["course_id"]][$question["tags"]-1]["tag_name"]?>
        </div>
        <div class="post-thread-view-title">
            <h4><?=$question["post_title"]?></h4>
        </div>
        <div class="post-thread-view-poster">
            <em><?=$question["poster_firstname"]." ".$question["poster_lastname"]?></em>
        </div>
        <div class="post-thread-view-body">
            <?=file_get_contents("../data/".$question["file"]);?>
        </div>
        <div class="post-thread-view-vote">
            <a href="" class="upvote">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </a>
            <h4 style="text-align:center"><?=$question["post_rating"]?></h4>
            <a href="" class="downvote">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </div>
    </div>
    <br />
</div>
<?php endif?>
