$(document).ready(function(){
    $('.persontag').tooltip(
    {
        trigger:"hover",
        placement:"bottom"
    }
    );
    $('.persontag').click(function(e){
        e.preventDefault();
        sender=$(this);
        $.ajax({
            url: 'follow.php',
            type: 'POST',
            data:{
                person:sender.data("fullname")
            },
            success: function(response){
                if (response=="SUCCESS"){
                    sender.removeClass('.persontag');
                    sender.addClass('.followedpersontag');
                    sender.data("title","");
                    $.showmsg("You are now following "+sender.data("fullname"));
                }
                else{
                    $.showmsg(response);
                }
            }
        }
        );
    }
    );
    $('.findcourse').typeahead({
        updater: function(element){ 
            $(".findcourse").css("width","80%");
            $(".findcourse").css("border-radius","4px 0 0 4px");
            $(".addcoursebutton").show();
            return element;
        },
        matcher: function(){ return true;},
        minLength: 2,
        source: function (query, process) {
            $(".addcoursebutton").hide();
            $(".findcourse").css("width","95%");
            $(".findcourse").css("border-radius","4px");
            $.ajax({
			        url:'querycourses.php',
			        type: 'POST',
			        data:{
			            q: query,
			            n: 8
			        },
			        success: function(response){
                        returndata=[];
			            var data=$.parseJSON(response);
				        for (i=0; i<data.length; i++)
				        {
				            returndata.push(data[i]["department"]+data[i]["number"]+" "+data[i]["name"]);
				        }
				        process(returndata);
			        }
		        });
        }
    });
    initpersonbox();
}
);

function initpersonbox()
{
$('.findperson').typeahead({
        updater: function(element){ 
            $(".oncompleteP").show();
            return element;
        },
        matcher: function(){ return true;},
        minLength: 1,
        source: function (query, process) {
            $(".oncompleteP").hide();
            $.ajax({
			        url:'querypeople.php',
			        type: 'POST',
			        data:{
			            q: query,
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
}
