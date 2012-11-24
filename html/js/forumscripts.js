/***********************************************************************
 *
 * Global JavaScript, if any.
 **********************************************************************/
 
 $(document).ready(function(){
		$(".coursetag").click(function(){
		    var tagactivated=$(this).hasClass("active");
		    inactivateall();
		    if (!tagactivated)
			    $(this).addClass("active");
			submit();
		});
		$(".course").click(function(e){
			if($(e.target).is('button')){
				e.preventDefault();
				return;
			}
			senderid=$(this).attr("id").substring(9);
			
			$("#tagcontainer"+senderid).collapse('toggle');
		});
		$(".subtag").click(function(){
		    var parts=$(this).attr("id").split('-');
		    var course=parts[1].substring(6);
		    var tagactivated=$(this).hasClass("active");
		    inactivateall();
		    if (!tagactivated)
			    $(this).addClass("active");
			$("#course"+course).addClass("active");
		    submit();
		});
					
		$(".tagcontainer").on('hidden', function () {
		    id=$(this).attr("id").substring(12);
			$.cookie("collapsed"+id,true);
		});
		$(".tagcontainer").on('shown', function () {
		    id=$(this).attr("id").substring(12);
			$.cookie("collapsed"+id,null);
		});
        sortmethod = $.cookie("sortmethod");
        if (sortmethod=="Date")
        {
            $("#viewbutton").text("Date");
            $("#sortmethod a").text("Helpfulness");
        }
        $("#searchposts").keyup(function(e){
            submit();
        }
        );
        $("#sortmethod a").click(function(e){
            e.preventDefault();
            sortmethod=$(this).text();
            $.cookie("sortmethod",sortmethod);
            if (sortmethod=="Date")
            {
                $("#viewbutton").text("Date");
                $("#sortmethod a").text("Helpfulness");
            }
            else
            {
                $("#viewbutton").text("Helpfulness");
                $("#sortmethod a").text("Date");
            }
            submit();
        });
        $(".post-container").on("click", ".post", function(e){
			if(!$(e.target).hasClass('persontag')){
                changethread($(this).data("course"),$(this).data("thread"));
            }
        });
        $('.upvote').click( function(e) {
            e.preventDefault();
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
                        var changedid="#rating"+sender.data("postclass")+"-"+sender.data("postid");
                        $(changedid).text(Number($(changedid).text()) + 1);
                  }
              });
    
              return false;
          });
        $('.downvote').click( function(e) {
            e.preventDefault();
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
                        var changedid="#rating"+sender.data("postclass")+"-"+sender.data("postid");
                        $(changedid).text(Number($(changedid).text()) - 1);
                  }
              });
    
              return false;
          });
    }
);

function inactivateall(){
    $(".active").each(function(){
        $(this).removeClass("active");
    }
    );
}
function submit(){

    selectedcourses="";
    selectedtags="";
    $(".active").each(function(){
        if ($(this).attr("id")[0]=="c")
            selectedcourses+=$(this).attr("id").substr(6)+",";
        else if ($(this).attr("id")[0]=="t")
        {
	        var parts=$(this).attr("id").split('-');
	        var id=parts[0].substring(3);
            selectedtags+=id+",";
        }
    }
    );
    url="index.php?"
    if (selectedcourses!="")
    {
        selectedcourses=selectedcourses.substr(0,selectedcourses.length-1);
        url+="scourses="+selectedcourses+"&";
    }
    if (selectedtags!="")
    {
        selectedtags=selectedtags.substr(0,selectedtags.length-1);
        url+="tags="+selectedtags+"&";
    }
    if ($("#searchposts").val()!="")
        url+="filter="+encodeURI($("#searchposts").val())+"&";
    if ($("#viewbutton").text()=="Helpfulness") method="post_rating";
    else method="posttime";
    url+="sort="+method;
    $.ajax({
        url:'forum.php',
        type: 'POST',
        data:{
            tags:selectedtags,
            scourses:selectedcourses,
            sort: method,
            filter: $("#searchposts").val()
        },
        success: function(response)
        {
            $(".post-container").html(response);
            window.history.pushState(null,'',url);
            return false;
        }
    }
    );
}
