
<div id="askModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Ask</h3>
    </div>
    <div class="modal-body">
    <div id="response">
    </div>
        <input id="title" name="title" placeholder="Title..." type="text" style="width: 510px"></input>
        <div id="title-error" style="color:red"></div>
        <textarea id="question" name="question" placeholder="Post your question here..." style="width: 510px; height: 150px"></textarea>
        <div id="question-error" style="color:red"></div>
        <div class="control-group">
            <select name="type">
                <option value="">Tags</option>
                <option value=pset>pset</option>
                <option value=essay>essay</option>
            </select>
            <div id="type-error" style="color:red"></div>
            <input id="psetnum" name="psetnum" type="text" style="width: 20px"></input>
            <div id="psetnum-error" style="color:red"></div>
        </div>
        <div class="control-group">
            <select name="courselist">
                <option value="">Courses</option>
                <?php $file = fopen("../includes/courses.json", "r"); ?>
                
                <?php $json = stream_get_contents($file); ?>
                <?php $courses = json_decode($json, true); ?>                
                <?php
                    foreach ($courses as $key => $value) 
                        print("<option value={$key}>{$key}</option>");
                ?>
            </select>
            <div id="courselist-error" style="color:red"></div>
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
			$("input[name=title]").blur(function(){
				if ($("input[name=title]").val()=="")
					$("#title-error").text("Please enter a title!");
				else
					$("#title-error").text("");
			});
			
			$("textarea[name=question]").blur(function(){
				if ($("textarea[name=question]").val()=="")
					$("#question-error").text("Please enter a question!");
				else
					$("#question-error").text("");
			});
			
			$("select[name=type]").blur(function(){
				if ($("select[name=type]").val()=="")
					$("#type-error").text("Please enter a tag type!");
				else
					$("#type-error").text("");
			});
			
			$("input[name=psetnum]").blur(function(){
				if ($("input[name=psetnum]").val()=="")
					$("#psetnum-error").text("Please enter a tag number!");
				else
					$("#psetnum-error").text("");
			});
			
			$("select[name=courselist]").blur(function(){
				if ($("select[name=courselist]").val()=="")
					$("#courselist-error").text("Please select a course!");
				else
				    $("#courselist-error").text("");
			});
			
			$("#submit").click(function(){
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
					  //  window.location.reload(true);
					    $("#courselist-error").html(response);
					}
				});
				return false;
			});
		});
</script>
