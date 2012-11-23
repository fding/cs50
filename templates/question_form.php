<div id="askModal" class="modal hide fade"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Ask</h3>
    </div>
    <div class="modal-body">
        <div id="response">
        </div>
        <input id="title" name="title" placeholder="Title..." type="text" style="width: 600px"></input>
        <textarea id="question" name="question" placeholder="Post your question here..." style="width: 600px; height: 220px"></textarea>
        <div class="control-group">
            <select name="type" style="width:100px">
                <option value="">Tags</option>
                <option value=pset>pset</option>
                <option value=essay>essay</option>
            </select>
            <input id="psetnum" name="psetnum" type="text" style="width: 20px; "></input>
            <select name="courselist" style="width:200px; margin-left:20px;">
                <option value="">Courses</option>
                <?php 
                    
                    foreach ($mycourses as $key) 
                        print("<option value=\"{$key["name"]}\">{$key["name"]}</option>");
                ?>
            </select>
            <span id="submit-error" style="color:red"></span>
        </div>
        <script type="text/javascript">
        $('#question').wysihtml5();
        </script>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" id="submit">Submit</button>
    </div>
</div>

<script>
		$(document).ready( function(){			
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
				    $.ajax({
					    url:'question.php',
					    type: 'POST',
					    data:{
						    title: $("input[name=title]").val(),
						    question: $("textarea[name=question]").val(),
						    type: $("select[name=type]").val(),
						    psetnum: $("input[name=psetnum]").val(),
						    course: $("select[name=courselist]").val()
					    },
					    success: function(response)
					    {
							    window.location.reload();
					    }
				    });
			    };
	        });
        });
</script>
