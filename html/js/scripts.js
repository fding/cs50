$(document).ready(function(){
        
        $('.findcourse').typeahead({
            updater: function(element){ 
                $(this).data("completed","true"); 
                return element;
            },
            matcher: function(){ return true;},
            minLength: 2,
            source: function (query, process) {
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
}
)
