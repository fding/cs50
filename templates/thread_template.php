<div class="thread">
    <div id="thread-panel">
        &nbsp;
        <i class="icon-chevron-left"></i>
        <em style="font-family:helvetica,sans-serif;">Back</em>
    </div>
    <script>
        $(document).ready(function(){
            $("#thread-panel").click(function(){
                history.back();
            }
            );
        }
        );
    </script>
    <div id="thread-container" class="post">
    <div>
        <?=$question["course"]?> <i class="icon-chevron-right"></i> 
        <?=$tags[$question["course_id"]][$question["tags"]-1]["tag_name"]?>
        <h4 style="position:absolute; top: 0%; left:20%">
            <?=$question["post_title"]?>
        </h4>
        <em style="position:absolute; top: 0.5%; right:10%">
            Asked by 
            <?=$question["poster_firstname"]." ".$question["poster_lastname"]?>
        </em>
    </div>
    <br />
    <?=file_get_contents("../data/".$question["file"]);?>
    </div>
</div>
