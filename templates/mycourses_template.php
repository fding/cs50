<link href="css/manage.css" rel="stylesheet"/>
<div class="leftpanel">
<h4>Change your account settings! </h4>
First name: <input name="firstname" class="input-medium" placeholder="<?=$_SESSION["firstname"]?>" type="text"/>
<br/>
Last name: <input name="firstname" class="input-medium" placeholder="<?=$_SESSION["lastname"]?>" type="text"/>
<hr />
Old password: <input name="oldpassword" class="input-medium" placeholder="old password" type="password"/><br/>
New password: <input name="newpassword" class="input-medium" placeholder="new password" type="password"/><br/>
Confirmation: <input name="confpassword" class="input-medium" placeholder="confirmation" type="password"/>
</div>
<div class="middlepanel">
    <div id="errormessage"></div>
    <h4>What courses are you taking?</h4>
    <table class="table">
        <?php foreach ($mycourses as $course):?>
        <tr>
            <td><?=$course["name"]?> </td>
            <td><button class="btn btn-medium btn-success removecoursebutton" data-course="<?=$course["id"]?>" type="button">Remove</button></td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td>
                <input type="text" data-id="1" id="newclass" class="findcourse" data-provide="typeahead" placeholder="Search for classes">
            </td>
            <td>
                <button class="btn btn-medium btn-success addcoursebutton" type="button">Add</button>
            </td>
        </tr>
    </table>
</div>
<div class="rightpanel">
    <h4>People you follow</h4>
    <table class="table" style="width:80%">
        <?php foreach ($followedpeople as $person):?>
        <tr>
            <td><?=$person["firstname"]." ".$person["lastname"]?> </td>
            <td><button class="btn btn-medium btn-success removefollowbutton" data-person="<?=$person["id"]?>" type="button">Remove</button></td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td>
                <input type="text" data-id="1" id="newfollow" class="findperson" data-provide="typeahead" placeholder="Search for classmates">
            </td>
            <td>
                <button class="btn btn-medium btn-success addfollowbutton" type="button">Add</button>
            </td>
        </tr>
    </table>
</div>
<script>
    $(document).ready(function(){
        $(".addcoursebutton").hide();
        $(".addfollowbutton").hide();
        $(".removecoursebutton").click(function(){
            sender=$(this);
			$.ajax({
				url:'removecourse.php',
				type: 'POST',
				data:{
				    course:sender.data("course")
				    
				},
				success: function(response){
				    if (response=="SUCCESS")
					location.reload();
				}
			});
        }
        );
        
        
        $(".addcoursebutton").click(function(){
            sender=$(this);
			$.ajax({
				url:'addcourse.php',
				type: 'POST',
				data:{
				    course:$("#newclass").val()
				},
				success: function(response){
				    if (response=="SUCCESS")
					    location.reload();
					else 
					    $.showmsg(response);
				}
			});
        }
        );
        $(".addfollowbutton").click(function(){
            sender=$(this);
			$.ajax({
				url:'follow.php',
				type: 'POST',
				data:{
				    person:$("#newfollow").val()
				},
				success: function(response){
				    if (response=="SUCCESS")
					    location.reload();
					else 
					    $.showmsg(response);
				}
			});
        }
        );
        
    }
    );
</script>
