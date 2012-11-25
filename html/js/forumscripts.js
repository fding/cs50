/***********************************************************************
 *
 * Global JavaScript, if any.
 **********************************************************************/

$(document).ready(function(){
    window.replyon=false;
    correctsize();
    $(window).resize(correctsize);
	        $("#thread-container").css("max-height",$(document).height()-78);
    $(".course-container").on("click",".coursetag",function(){
	    var tagactivated=$(this).hasClass("active");
	    inactivateall();
	    if (!tagactivated)
		    $(this).addClass("active");
		submit();
	});
	            $(".addcoursebutton").hide();
    
            $(".addcoursebutton").click(function(){
                sender=$(this);
	            $.ajax({
		            url:'addcourse.php',
		            type: 'POST',
		            data:{
		                course:$(".findcourse").val()
		            },
		            success: function(response){
		                if (response=="SUCCESS")
		                {
					        $.showmsg("Your course has been added.");
					        if (refreshmenu())
					            submit("-1");
			            }
			            else 
			                $.showmsg(response);
		            }
	            });
            });
	
	
	$(".course-container").on("click",".course",function(e){
		if($(e.target).is('button')){
			e.preventDefault();
			return;
		}
		senderid=$(this).attr("id").substring(9);
		$("#tagcontainer"+senderid).collapse("toggle");
	});
	
    $(".course-container").on("hidden", ".tagcontainer", function () {
	    id=$(this).attr("id").substring(12);
		$.cookie("collapsed"+id,true);
	});
	$(".course-container").on("shown", ".tagcontainer",function () {
	    id=$(this).attr("id").substring(12);
		$.cookie("collapsed"+id,null);
	});
	
	
	
	$(".course-container").on("click",".subtag", function(){
	    var parts=$(this).attr("id").split('-');
	    var course=parts[1].substring(6);
	    var tagactivated=$(this).hasClass("active");
	    inactivateall();
	    if (!tagactivated)
		    $(this).addClass("active");
		$("#course"+course).addClass("active");
	    submit();
	});
	
    $(".course-container").on("click",".removecourse",function(e)
    {
        sender=$(e.target);
        $.ajax({
            url: "removecourse.php",
            type: "POST",
            data: {
                course:sender.data("course")
            },
            success:function(response){
                if (response=="SUCCESS")
                {
                    $.showmsg("Deletion successful");
			        if (refreshmenu(sender.data("course")))
			            submit();
                }
                else
                {
                    $.showmsg(response);
                }
            }
        });
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
    $(".thread").on("click",".removepost",function(e)
    {
        sender=$(e.target);
        
        $.ajax({
            url: "deletepost.php",
            type: "POST",
            data: {
                courseid:sender.data("course"),
                postid:sender.data("postid")
            },
            success:function(response){
                if (response=="SUCCESS")
                {
                    window.location = "index.php?scourses="+sender.data("course")+"&sort=posttime&course="+sender.data("course")+"&thread="+sender.data("postid")
                    $.showmsg("Deletion successful");
                    submit();
                }
                else
                {
                    $.showmsg(response);
                }
            }
        }
        );
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
	$("#reply-collapse").click(function(e){
	    e.preventDefault();
	    var targetcontainer, targetform;
	    if (window.replyon)
	    {
	        targetcontainer=($(document).height()-78)+"px";
	        targetform="0px";
	        window.replyon=false;
        }
        else
        {
            targetcontainer=($(document).height()-261)+"px";
            targetform="180px";
            window.replyon=true;
        }
        
        $("#thread-container").animate({"max-height":targetcontainer},300);
        if (window.replyon)
        $("#reply-area").animate({height:targetform},300,function(){$("#reply").focus()});
        else
        $("#reply-area").animate({height:targetform},300)
	}
	);
    $("#replysubmit").click(function(){
    
	    var file = question["file"];
	    var courseid = question["course_id"];
	    $.ajax({
		    url:'reply.php',
		    type: 'POST',
		    data:{
			    reply: $("textarea[name=reply]").val(),
                courseid: $("#thread-info").data("course"),
                postid:  $("#thread-info").data("postid")
		    },
		    success: function(response)
		    {
		        $.showmsg("Your reply has been posted.");
			    changethread($("#thread-info").data("course"), $("#thread-info").data("postid"));
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
function refreshmenu(submitter){
    selectedcourses="";
    selectedtags="";
    $(".active").each(function(){
        if (typeof($(this).attr("id"))=="undefined") return;
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
    if (selectedcourses!="")
        selectedcourses=selectedcourses.substr(0,selectedcourses.length-1);
    if (selectedtags!="")
        selectedtags=selectedtags.substr(0,selectedtags.length-1);
    if ($(".active").size()!=0) submit();
    $.ajax({
        url:'menu.php',
        type: 'POST',
        data:{
            scourses:selectedcourses,
            stags:selectedtags
        },
        success: function(response){
            $(".course-container").html(response);
        }
    }
    );
    if (selectedcourses!=submitter) return false;
    return true;
}

function getselected(){
    var selectedcourses, selectedtags, filter, method;
    selectedcourses="";
    selectedtags="";
    $(".active").each(function(){
        if (typeof($(this).attr("id"))=="undefined") return;
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
    if (selectedcourses!="")
    {
        selectedcourses=selectedcourses.substr(0,selectedcourses.length-1);
    }
    if (selectedtags!="")
    {
        selectedtags=selectedtags.substr(0,selectedtags.length-1);
    }
    filter=$("#searchposts").val();
    if ($("#viewbutton").text()=="Helpfulness") method="post_rating";
    else method="posttime";
    return [selectedcourses,selectedtags,filter,method];

}

function makeurl(thecourse,thethread){
    var url= "index.php?";
    var selectedels=getselected();
    if (selectedels[0]!="") 
        url+="scourses="+selectedels[0]+"&";
    if (selectedels[1]!="")
        url+="tags="+selectedels[1]+"&";
    if (selectedels[2]!="")
        url+="filter="+selectedels[2]+"&";
    url+="sort="+selectedels[3]+"&";
    url+="course="+thecourse+"&thread="+thethread;
    return url;
}

function submit(){
    var selectedcourses, selectedtags, filter,method;
    var selectedels=getselected();
    selectedcourses=selectedels[0];
    selectedtags=selectedels[1];
    filter=selectedels[2];
    method=selectedels[3];
    url="index.php?"
    if (selectedcourses!="")
    {
        url+="scourses="+selectedcourses+"&";
    }
    if (selectedtags!="")
    {
        url+="tags="+selectedtags+"&";
    }
    if ($("#searchposts").val()!="")
        url+="filter="+encodeURI(filter)+"&";
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

function correctsize(){
    if (window.replyon)
        $("#thread-container").css("max-height",$(document).height()-261);
    else 
        $("#thread-container").css("max-height",$(document).height()-78);
    if ($(document).width()<900)
    {
        $(".navigation").width(143);
        $(".forum").width(324);
        $(".thread").width(401);
        $(".forum").css("left","162px");
        $(".thread").css("left","496px");
        $("#middle").width(900);
    }
    else
    {
        $(".navigation").css("width","15.9%");
        $(".forum").css("width","36%");
        $(".thread").css("width","44.55%");
        $(".forum").css("left","18%");
        $(".thread").css("left","55.1%");
        $("#middle").css("width","99.9%");
    }
} 

function changethread(thecourse,thethread){
    $("#thread-container").html("Loading...");
	$.ajax({
		url:'thread.php',
		type: 'POST',
		data:{
		    thread:thethread,
		    course:thecourse
		},
		success: function(response)
		{
		    $("#thread-container").html(response);
		    window.history.pushState(null,'',makeurl(thecourse,thethread))
		}
	});
}
