/***********************************************************************
 *
 * Javascript for forum features.
 **********************************************************************/

$(document).ready(function(){

/************************************************************************
* Initialize document
************************************************************************/
    window.replyon=false;
    correctsize();
    
    sortmethod = $("#phpsortmethod").val();
    if (sortmethod=="lastedit")
    {
        $("#viewbutton").text("Date");
        $("#sortmethod a").text("Helpfulness");
    }

/*************************************************************************
*  Window resizing code: correctly displays the size of the window in 
*  different conditions.
*************************************************************************/
    
    $(window).resize(correctsize);
    $(".course-container").on("click",".coursetag",function(){
	    var tagactivated=$(this).hasClass("active");
	    inactivateall();
	    if (!tagactivated)
		    $(this).addClass("active");
		submit();
	});
	
/*************************************************************************
*  Navigation Menu code
************************************************************************/
    // Adding and removing courses 
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
    
    // Code for Collapsible menus
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
/************************************************************
*Submitting a question
************************************************************/
$("#askModal").on("show",function()
{
    $("select[name='courselist']").html("<option value=\"\">Courses</option>");
    $(".coursetag").each(function(){
        $("select[name='courselist']").append("<option value=\""+$(this).attr("id").substr(6)+"\">"+$(this).text()+"</option>");
    }
    )
});
$("#submit").click(function(){
	if ($("input[name=title]").val()=="")
		$("#submit-error").text("Please enter a title!");
	else if ($("textarea[name=question]").val()=="")
		$("#submit-error").text("Please enter a question!");
	else if ($("select[name=type]").val()=="")
		$("#submit-error").text("Please enter a tag type!");
	else if ($("input[name=psetnum]").val()=="")
		$("#submit-error").text("Please enter a tag number!");
	else if ($("select[name=courselist]").val()=="")
		$("#submit-error").text("Please select a course!");
	else
	{
	    $("#submit").text("Submitting...");
	    var privacy=$("select[name='privacy']").val()+","+$("#visibleto").val();
	    $.ajax({
		    url:'question.php',
		    type: 'POST',
		    data:{
			    title: $("input[name=title]").val(),
			    question: $("textarea[name=question]").val(),
			    type: $("input[name=type]").val(),
			    psetnum: $("input[name=psetnum]").val(),
			    course: $("select[name=courselist]").val(),
			    privacy: privacy
		    },
		    success: function(response)
		    {
		        // TODO Need Error Checking !!!
		        /* Code for auto-updating.
		        var postinfo=new Array();
		        postinfo["posterid"]=$("#userid").val();
		        postinfo["link"]=0;
		        postinfo["asker"]=$("#userid").val();
		        postinfo["coursename"]=$("select[name=courselist]").val();
		        postinfo["poster_firstname"]=$("#userfirstname").val();
		        postinfo["poster_lastname"]=$("#userlastname").val();
		        document.getElementById('updateframe').contentWindow.edittedpost(postinfo);*/
			    submit();
			    $.showmsg(response);
			    $("#askModal").modal('hide');
			    $.showmsg("Your question has been posted.");
			    $("#submit").text("Submit");
			    return true;
		    }
	    });
    };
});

    $('#visibleto').typeahead({
        updater: function(element){ 
            $(".oncompleteP").show();
            var names=$("#visibleto").val().split(",");
            var output="";
            for (i=0; i<names.length-1;i++){
                output+=$.trim(names[i])+", ";
            }
            return output+=element+", ";
        },
        matcher: function(){ return true;},
        minLength: 1,
        source: function (query, process) {
            var names=query.split(",");
            var curname=names[names.length-1];
            $.ajax({
			        url:'querypeople.php',
			        type: 'POST',
			        data:{
			            q: curname,
			            n: 8,
			            notme:1
			        },
			        success: function(response){
                        returndata=[];
			            var data=$.parseJSON(response);
				        for (i=0; i<data.length; i++)
				        {
				            returndata.push(data[i]["firstname"]+" "+data[i]["lastname"]);
				        }
				        process(returndata);
			        }
		        });
        }
    });
    
    $("select[name='privacy']").change(function(){
        if ($(this).val()==3)
            $("#visibleto").show();
        else
            $("#visibleto").hide();
    });

/************************************************************
* Middle Panel code
*************************************************************/

	// Changing sort options
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
    
    // Searching for posts
    $("#searchposts").keyup(function(e){
        submit();
    });
    
    // Selection of a thread
    $(".post-container").on("click", ".post", function(e){
        $(this).removeClass("unread");
		if(!$(e.target).hasClass('persontag')){
            changethread($(this).data("course"),$(this).data("thread"));
        }
    });

/************************************************************
* Right panel code
************************************************************/
    $(".thread").on("click",".removepost",function(e)
    {
        var sender=$(e.target);
        var course=$("#thread-info").data("course");
        var post=sender.parent().find("[type=hidden]").data("postid")
        $.ajax({
            url: "deletepost.php",
            type: "POST",
            data: {
                courseid:course,
                postid:post
            },
            success:function(response){
                if (response=="SUCCESS")
                {
                    $.showmsg("Deletion successful");
                    submit();
                    changethread(course,$("#thread-info").data("postid"));
                }
                else
                {
                    $.showmsg(response);
                }
            }
        }
        );
    });
    var old = "";
	$("#thread-container").on("click",".editpostbutton",function()
        {
	    if (typeof(window.lasteditsender)!="undefined"){
            if ($(this)==window.lasteditsender)
            {
                return;
            }
            else
            {
                window.lasteditsender.html(old);
            }
        }
        window.lasteditsender=$(this).parent().find(".post-body");
        var contents=stripMathJax(window.lasteditsender);
        old=window.lasteditsender.html();
		var string = "<textarea class = 'editfield'>"+contents+"</textarea>" 
		string+="<button class='btn btn-danger editcancel'>Cancel</button>";
		string+="<button class='btn btn-primary editsubmit'>Update</button>";
		window.lasteditsender.html(string);
        $('textarea[class = editfield]').wysihtml5({
	    "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
	    "emphasis": true, //Italics, bold, etc. Default true
	    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
	    "html": false, //Button which allows you to edit the generated HTML. Default false
	    "link": false, //Button to insert a link. Default true
	    "image": true, //Button to insert an image. Default true,
	    "color": false //Button to change color of font  
        });
        $('textarea[class = editfield]').css("height","auto");
        $('textarea[class = editfield]').focus();
    });
    
    $("#thread-container").on("click",".editsubmit", function(e){
        // No changes => No need to submit
        if ($(".editfield").val()==stripMathJax($(old)))
        {
            window.lasteditsender.html(old);
            return;
        }
        sender=$(this).parent().parent().find("[type='hidden']");
        $.ajax({
	        url:'edit.php',
	        type: 'POST',
	        data:{
		        editpost: $(".editfield").val(),
		        courseid: $("#thread-info").data("course"),
		        postid: sender.data("postid")
	        },
            success:function(response){
                if (response=="SUCCESS")
	            {
		            $.showmsg("Your post has been edited.");
		            changethread($("#thread-info").data("course"),$("#thread-info").data("postid"));
		            return true;
	            }
	            else $.showmsg(response);
            }
        });
    });        
    $("#thread-container").on("click",".editcancel", function(){
        window.lasteditsender.html(old);
        delete window.lasteditsender; 
    });
        
    
    $("#thread-container").on("click",'.upvote',function(e) {
        e.preventDefault();
        var votebutton=$(this);
        var sender = $(this).parent().parent().find("[type=hidden]");
          $.ajax({
              url: 'vote.php',
              type: 'POST',
              data: {
                  postid: sender.data("postid"),
                  postclass: $("#thread-info").data("course"),
                  link: $("#thread-info").data("postid"),
                  type: "+ 1"
              },
              success: function(response) {
                   var canvoteagain=false;
                   var votedownbutton=votebutton.parent().find(".voteddown")
                   if (votedownbutton.length==1) canvoteagain=true;
                  votedownbutton.attr('title','Indicate that you found the post unhelpful.');
                  votedownbutton.attr('class','downvote');
                  if (response == 1)
                  {
                        var changedid="#rating"+$("#thread-info").data("course")+"-"+sender.data("postid");
                        $(changedid).text(Number($(changedid).text()) + 1);
                        if (!canvoteagain)
                        {
                            votebutton.attr('class', 'votedup');
                            votebutton.attr('title','You have already voted up.');
                        }
                  }
              }
          });

          return false;
      });
    
    $("#thread-container").on("click",'.downvote',function(e) {
        e.preventDefault();
        var votebutton=$(this);
        sender = $(this).parent().parent().find("[type=hidden]");
          $.ajax({
              url: 'vote.php',
              type: 'POST',
              data: {
                  postid: sender.data("postid"),
                  postclass: $("#thread-info").data("course"),
                  link: $("#thread-info").data("postid"),
                  type: "- 1"
              },
              success: function(response) {
                var canvoteagain=false;
                var voteupbutton=votebutton.parent().find(".votedup")
                if (voteupbutton.length==1) canvoteagain=true;
                voteupbutton.attr('class','upvote');
                voteupbutton.attr('title','Indicate that you found the post helpful.');
                  if (response == 1)
                  {
                        var changedid="#rating"+$("#thread-info").data("course")+"-"+sender.data("postid");
                        $(changedid).text(Number($(changedid).text()) - 1);
                        if (!canvoteagain)
                        {
                            votebutton.attr('class', 'voteddown');
                            votebutton.attr('title', 'You have already voted down.');
                        }
                  }
              }
          });

          return false;
      });
    
    $(document).click(function(e){
        if (!$(e.target).hasClass("popover")&&$(e.target).attr("id")!="inviteexpert"&&
            $(".popover").find($(e.target)).length==0 &&!$(e.target).hasClass("findperson")) 
            $("#inviteexpert").popover("hide");
    });
      
    $("#thread-container").on("click","#inviteexpert",function(){
        $(this).popover("show");
        initpersonbox(); 
        $(".oncompleteP").hide();
    });
    
    $("#thread-container").on("click",".oncompleteP",function(){
        $.ajax({
            url:"invite.php",
            type:"POST",
            data:{
                course: $("#thread-info").data("course"),
                post:$("#thread-info").data("postid"),
                person:$(".findperson").val()
            },
            success:function(response){
                //$.showmsg(response);
                $.showmsg($(".findperson").val()+" was invited to answer this question");
                $(".findperson").val("");
                $(".oncompleteP").hide(); 
            }
        });
    });
    $("#thread-container").on("click","#subscribetopost",function(){
        $.ajax({
            url: "subscribe.php",
            type: "POST",
            data:{
                course: $("#thread-info").data("course"),
                post:$("#thread-info").data("postid")
            },
            success:function(response){
                $.showmsg(response);
            }
        });
    });
	$("#reply-collapse").click(function(e){
	    e.preventDefault();
	    var targetcontainer, targetform;

	    if (window.replyon)
	    {
	        targetcontainer=($(".thread").height()-20)+"px";
	        targetform="0px";
	        window.replyon=false;
        }
        else
        {
            targetcontainer=($(".thread").height()-200)+"px";
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
	    $("#replysubmit").text("Submitting...");
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
		        $("#replysubmit").text("Submit");
		        $.showmsg("Your reply has been posted.");
			    changethread($("#thread-info").data("course"), $("#thread-info").data("postid"));
		    }
	    });
	    return false;
    });
    
}
);

/*******************************************************************************************
* Helper Functions
********************************************************************************************/

// Inactivate all buttons
function inactivateall(){
    $(".active").each(function(){
        $(this).removeClass("active");
    }
    );
}

// Refresh the course menu
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

// Find all active tags
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
    if ($("#viewbutton").text()=="Helpfulness") method="helpfulness";
    else method="lastedit";
    return [selectedcourses,selectedtags,filter,method];

}

// Creates an url to pusn to history
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

// refreshes middle panel
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

function initinvite(){
    var inside='<input type="text" data-id="1" id="newfollow" class="findperson" data-provide="typeahead" placeholder="Search for friends"/>';
    inside+='<button class="oncompleteP">Invite</button>';
    $("#inviteexpert").popover({
        html: true,
        title: "Invite a friend to answer this question!",
        content: inside,
        toggle:'manual',
        placement:'top'
    });
}

// Refreshes the right-most panel
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

// Correctly sizes all elements on the screen.
function correctsize(){
    /*$(".thread").css("max-height",$("#middle").height()+"px");
    $(".forum").css("max-height",$("#middle").height()+"px");
    $(".navigation").css("max-height",$("#middle").height()+"px");*/
    var middleheight=$("#middle").height();
    /*$(".thread").height(middleheight);
    $(".forum").height(middleheight);
    $(".navigation").height(middleheight);*/
    if (window.replyon)
        $("#thread-container").css("max-height",$(".thread").height()-200+"px");
    else 
        $("#thread-container").css("max-height",$(".thread").height()-20+"px");
    $(".post-container").css("max-height",($(".forum").height()-8)+"px");
    if ($(document).width()<900)
    {
        $(".navigation").width(143);
        $(".forum").width(324);
        $(".thread").css("right","0px");
        $(".forum").css("left","162px");
        $(".thread").css("left","496px");
        $("#middle").width(900);
    }
    else
    {
        $(".navigation").css("width","15.9%");
        $(".forum").css("width","36%");
        $(".thread").css("right","0px");
        $(".forum").css("left","18%");
        $(".thread").css("left","55.1%");
        $("#middle").css("width","99.9%");
    }
    if (parseFloat($("#thread-container").css("max-height"))<$("#thread-container").height())
    {
        $("#thread-container").css("margin-right","6px");
    }
    else
        $("#thread-container").css("margin-right","0px");
} 

// Display a notification message
function showmsg(message){
    $.showmsg(message);
}

// Return the delatexified html of given element
function stripMathJax(obj){
    var newobj=obj.clone();
    newobj.find(".MathJax_Preview").remove();
    newobj.find(".MathJax").remove();
    var script=newobj.find("script[type='math/tex']");
    script.append("$$");
    script.prepend("$$");
    script.contents().unwrap();
    return newobj.html();
}

