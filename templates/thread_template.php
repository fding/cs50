<?php if (!empty($question)):?>
<script>
    $(document).ready(function(){$("#reply-collapse").show();
        initinvite();
        MathJax.Hub.Queue(["Typeset",MathJax.Hub,"thread-container"]);
    });
</script>
    <div class="post-thread-view">
    <input type="hidden" id="thread-info" data-course="<?=$question["course_id"]?>" data-postid="<?=$question["post_id"]?>"/>
    <?php if($question["poster_id"]==$_SESSION["id"]): ?>
        <button type="button" class="close removepost" aria-hidden="true">&times;</button>
        <button type="button" class= "close editpostbutton" aria-hidden="true"><i class="icon-edit"></i>
        </button>
    <?php endif;?>
        <div class="post-thread-view-tag">
            <?=$question["course"]?> <i class="icon-chevron-right"></i><?=$tags[$question["course_id"]][$question["tags"]]["tag_name"]?>
        </div>
        <div class="post-thread-view-title"><?=$question["post_title"]?></div>
        <div class="post-thread-view-poster"><em><?=$question["poster_firstname"]." ".$question["poster_lastname"]?></em> &nbsp;</div>
            <?php 
                if(file_exists("../data/".$question["file"]))
                    $contents = file_get_contents("../data/".$question["file"]);
                else
                    $contents ="Corrupted file";
            ?>
            <?php
                $votestatus=0;
                $votepath="../data/posts/".$question["course_id"]."/".$question["post_id"]."/votes".$question["post_id"];
                
                if(file_exists($votepath))
                {
                    $votes = json_decode(file_get_contents($votepath), true);
                    if (!empty($votes["{$_SESSION["id"]}"])) $votestatus=$votes[$_SESSION["id"]];
                }
            ?>
            <div class="post-thread-view-vote">
                <?php if ($votestatus==1):?>
                <a class="votedup" title="You have already voted up." href="#"></a>
                <?php else:?>
                <a class="upvote" title="Indicate that you found the post helpful." href="#"></a>
                <?php endif;?>
                <h4 id=<?="rating".$question["course_id"]."-".$question["post_id"]?>><?=$question["post_rating"]?></h4>
                <?php if($votestatus==-1):?>
                <a class="voteddown" title="You have already voted down." href="#"></a>
                <?php else:?>
                <a class="downvote" title="Indicate that you found the post unhelpful." href="#"></a>
                <?php endif;?>
            </div>
            <?php if(!empty($question["privateto"])):?><div class="seenby">
            <?php 
            print("Seen only by me");
            foreach($question["privateto"] as $person){
                if ($person==$_SESSION["firstname"]." ".$_SESSION["lastname"] || $person==$question["poster_firstname"]." ".$question["poster_lastname"])
                    continue;
                else print(", ".$person);
            }
            ?>
            </div><?php endif;?>
            <div class="post-body"><?=$contents?></div> 
            <button id="subscribetopost" title="Get email updates everytime someone responds." class="btn btn-inverse">Subscribe to this post</button>
            <button id="inviteexpert" title="Invite a friend to answer this question." class="btn btn-inverse">Invite a friend!</button>
    </div>
    <?php foreach($replies as $reply):?>
    <div class="reply-post">
        <input type="hidden" data-course="<?=$reply["course_id"]?>" data-postid="<?=$reply["post_id"]?>"/>
        <?php if ($reply["poster_id"]==$_SESSION["id"]):?>
        <button type="button" class="close removepost" aria-hidden="true">&times;</button>
        <button type="button" class="close editpostbutton" aria-hidden="true"><i class="icon-edit"></i>
        </button>
        <?php endif;?>
        <div class="reply-post-poster"><em><?=$reply["poster_firstname"]." ".$reply["poster_lastname"]?></em></div>
        <?php
            $votestatus=0;
            $votepath="../data/posts/".$question["course_id"]."/".$question["post_id"]."/votes".$reply["post_id"];
            if(file_exists($votepath))
            {
                $votes = json_decode(file_get_contents($votepath), true);
                if (!empty($votes["{$_SESSION["id"]}"])) $votestatus=$votes[$_SESSION["id"]];
            }
        ?>
        <div class="post-thread-view-vote">
            <?php if ($votestatus==1):?>
            <a class="votedup" title="You have already voted up." href="#"></a>
            <?php else:?>
            <a class="upvote" title="Indicate that you found the post helpful." href="#"></a>
            <?php endif;?>
            <h4 id=<?="rating".$reply["course_id"]."-".$reply["post_id"]?>><?=$reply["post_rating"]?></h4>
            <?php if($votestatus==-1):?>
            <a class="voteddown" title="You have already voted down." href="#"></a>
            <?php else:?>
            <a class="downvote" title="Indicate that you found the post unhelpful." href="#"></a>
            <?php endif;?>
        </div>
            <?php 
                if(file_exists("../data/".$reply["file"]))
                    $contents = file_get_contents("../data/".$reply["file"]);
                else
                    $contents ="Corrupted file";
            ?>
        <div class="post-body"><?=$contents?></div>
    </div>
    <?php endforeach;?>
<?php endif?>
