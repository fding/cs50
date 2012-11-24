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
                $(".addcoursebutton").show();
                return element;
            },
            matcher: function(){ return true;},
            minLength: 2,
            source: function (query, process) {
                $(".addcoursebutton").hide();
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
        $('.findperson').typeahead({
            updater: function(element){ 
                $(".addfollowbutton").show();
                return element;
            },
            matcher: function(){ return true;},
            minLength: 1,
            source: function (query, process) {
                $(".addfollowbutton").hide();
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
)
