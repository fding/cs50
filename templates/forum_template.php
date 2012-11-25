<?php if (!empty($posts)): ?>
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
        </div>
        <div class="postdate">
            <?php $posttime = strtotime($post["posttime"]); 
            $now = time();
            $diff=$now-$posttime;
            if($diff < 60)
                print($diff." seconds ago");
            else if($diff < 60*60)
            {
                $diff = round($diff / 60);
                print($diff." minutes ago");
            }
            else if($diff < 60*60*24)
            {
                $diff = round($diff/ (60*60));
                print($diff." hours ago");
            }
            else if($diff < 60*60*24*365)
            {
                $diff = round($diff/ (60*60*24));
                print($diff." days ago");
            }
            else
            {
                $diff = round($diff/ (60*60*24*365));
                print($diff." years ago");
            }?>
        </div>
    </div>
<?php endforeach?>
<?php endif;?>
