<div style="text-align:center;width:80%;margin-left:50px;margin-top:20px">
<h4>
Hello, <?=$user["firstname"]?>! Please tell us what courses you are taking.
</h4>
<table class="table">
    <?php foreach ($mycourses as $course):?>
    <tr>
        <td><?=$course["name"]?> </td>
        <td><button class="btn btn-medium btn-success removecoursebutton" data-course=<?=$course["id"]?> type="button">Remove</button></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td>
            <input type="text" data-id="1" id="newclass" class="findcourse" data-provide="typeahead" placeholder="Search for classes">
        </td>
        <td>
            <button class="btn btn-medium btn-success" id="addcoursebutton" type="button">Add</button>
        </td>
    </tr>
</table>
</div>
<script>
    $(document).ready(function(){
        $(".removecoursebutton").click(function(){
            sender=$(this);
			$.ajax({
				url:'removecourse.php',
				type: 'POST',
				data:{
				    course:sender.data("course")
				},
				success: function(response){
					location.reload();
				}
			});
        }
        );
        
        $("#classtoadd").change(function(){
            if ($("#classtoadd").data("complete")=="true")
                $("#addcoursebutton").show();
            else
                $("#addcoursebutton").show();
        }
        )
        
        $("#addcoursebutton").click(function(){
            sender=$(this);
			$.ajax({
				url:'addcourse.php',
				type: 'POST',
				data:{
				    course:$("#newclass").val()
				},
				success: function(response){
					location.reload();
				}
			});
        }
        );
        
    }
    );
</script>
