<div class="forum">
	<?php
		// The control panel: searchbox, and sort method
	?>
    <div id="panel" style="position: relative">
        <input type="text" class="input-large search-query" placeholder="Search for posts"/>
        <div style="position: absolute; top:0%; left: 75%">
            Sort by 
            <div id="sortmethod" class="btn-group">
                <button id="viewbutton" class="btn">Helpfulness</button>
                <button class="btn dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a tabindex="-1" href="#">Date</a></li>
                </ul>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            sortmethod = $.cookie("sortmethod");
            if (sortmethod=="Date")
            {
                $("#viewbutton").text("Date");
                $("#sortmethod a").text("Helpfulness");
            }
            $("#sortmethod a").click(function(){
                $.cookie("sortmethod",$(this).text());
                if ($(this).text()=="Helpfulness")
                    sortresults("post_rating");
                if ($(this).text()=="Date")
                    sortresults("posttime");
            }
            );
        });
        function sortresults(method)
        {
            selectedcourses="";
            selectedtags="";
            $(".active").each(function(){
                if ($(this).attr("id")[0]=="c")
                    selectedcourses+=$(this).attr("id").substr(6)+",";
                else if ($(this).attr("id")[0]=="t")
                    selectedtags+=$(this).attr("id").substr(3)+",";
            }
            );
            url="index.php?"
            if (selectedcourses!="")
            {
                selectedcourses=selectedcourses.substr(0,selectedcourses.length-1);
                url+="courses="+selectedcourses+"&";
            }
            if (selectedtags!="")
            {
                selectedtags=selectedtags.substr(0,selectedcourses.length-1);
                url+="tags="+selectedtags+"&";
            }
            url+="sort="+method;
            window.location.replace(url);
            return;
        }
    </script>
	<?php
		// The gigantic box that contains links to all posts
	?>
    <div class="post-container">
<?php foreach ($posts as $post):?>
        <div class="post">
            <div class="posttags">
                <?=$post["course"]?> <i class="icon-chevron-right"></i> 
                <?=$tags[$post["course_id"]][$post["tags"]-1]["tag_name"]?>
            </div>
            <div class="posttitle">
                <a href=<?="\"thread.php?course=".$post["course_id"]."&thread=".$post["post_id"]."\""?>> <?=$post["post_title"]?> </a>
            </div>
            <div class="postauthor">
                <em>Asked by </em><?=$post["poster_firstname"]." ".$post["poster_lastname"]?>
            </div>
            <div class="postdate">
                <?=$post["posttime"]?>
            </div>
        </div>
<?php endforeach?>
	</div>
</div>
