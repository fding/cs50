<?php if (!empty($posts)): ?>
<?php foreach ($posts as $post):?>
    <div class="post <?php if(!$post["read"]) print("unread")?>" data-course="<?=$post["course_id"]?>" data-thread="<?=$post["post_id"]?>">
        <div class="posttags">
            <?=$post["course"]?> <i class="icon-chevron-right"></i> 
            <?=$tags[$post["course_id"]][$post["tags"]]["tag_name"]?>
        </div>
        <div class="posttitle">
            <?=$post["post_title"]?>
        </div>
        <span class="postauthor">
            <em>Asked by </em>
            <?php if ($post["poster_id"]==$_SESSION["id"]):?>
             me
            <?php elseif($post["poster_firstname"]=="Anonymous"):?>
            Anonymous
            <?php else: ?>
            <a class="persontag" data-title="Follow <?=$post["poster_firstname"]?>" data-fullname="<?=$post["poster_firstname"]." ".$post["poster_lastname"]?>"><?=$post["poster_firstname"]." ".$post["poster_lastname"]?></a>
            <?php endif;?>
            <?php $posttime = strtotime($post["posttime"]); 
            $now = time();
            $diff=$now-$posttime;
            if($diff < 60)
                print($diff." s. ago");
            else if($diff < 60*60)
            {
                $diff = round($diff / 60);
                print($diff." m. ago");
            }
            else if($diff < 60*60*24)
            {
                $diff = round($diff/ (60*60));
                print($diff." h. ago");
            }
            else if($diff < 60*60*24*365)
            {
                $diff = round($diff/ (60*60*24));
                print($diff." d. ago");
            }
            else
            {
                $diff = round($diff/ (60*60*24*365));
                print($diff." y. ago");
            }
            ?>
        </span>
        <span class="postdate">
        <?php 
            print(count($post["replies"])." replies");?>
        </span>
    </div>
<?php endforeach?>
<?php endif;?>
