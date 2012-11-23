<?php if (!empty($question)):?>
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
            <a data-postid = <?= $question["post_id"] ?> data-postclass = <?= $question["course_id"] ?>  href="" class="upvote">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </a>
            <h4 style="text-align:center" id=<?="rating".$question["course_id"]."-".$question["post_id"]?>><?=$question["post_rating"]?></h4>
            <a data-postid = <?= $question["post_id"] ?> data-postclass = <?= $question["course_id"] ?>  href="" class="downvote">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
        </div>
    </div>
    <br />
</div>

<script>
    <?= //these two scripts call vote.php to update the helpfulness  ?>
    
    $(document).ready(function(){
        $('.upvote').click( function() {
            sender = $(this);
              $.ajax({
                  url: 'vote.php',
                  type: 'POST',
                  data: {
                      postid: sender.data("postid"),
                      postclass: sender.data("postclass"),
                      type: "+ 1"
                  },
                  success: function(response) {
                        $("<?="#rating".$question["course_id"]."-".$question["post_id"]?>").text(Number($("<?="#rating".$question["course_id"]."-".$question["post_id"]?>").text()) + 1);
                  }
              });
    
              return false;
          });
      })
      
    $(document).ready(function(){
        $('.downvote').click( function() {
            sender = $(this);
              $.ajax({
                  url: 'vote.php',
                  type: 'POST',
                  data: {
                      postid: sender.data("postid"),
                      postclass: sender.data("postclass"),
                      type: "- 1"
                  },
                  success: function(response) {
                        $("<?="#rating".$question["course_id"]."-".$question["post_id"]?>").text(Number($("<?="#rating".$question["course_id"]."-".$question["post_id"]?>").text()) - 1);
                  }
              });
    
              return false;
          });
      })
</script>
<?php endif?>
