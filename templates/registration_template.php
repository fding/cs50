<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Register</h3>
	</div>
	<div class="modal-body">
		<div id="response">
		</div>
		<form>
			<fieldset>
				<div class="control-group">
					<input name="firstname" placeholder="First Name" type="text"/>
					<input name="lastname" placeholder="Last Name" type="text"/>
					
					
					<div id="name-error" style="color:red">
					</div>
				</div>
				<div class="control-group">
					<input name="password" placeholder="Password" type="password"/>
					<div id="password-error" style="color:red">
					</div>
				</div>
				<div class="control-group">
					<input name="confirmation" placeholder="Password Confirmation" type="password"/>
					<div id="confirmation-error" style="color:red">
					</div>
				</div>
				<div class="control-group">
					<input name="email" placeholder="Harvard College Email" type="text"/>
					<div id="email-error" style="color:red">
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary" id="submit">Submit</button>
	</div>
</div>
<?php
	// Now add event handlers and client-side error checking code
?>
<script>
$(document).ready( function(){
	$("#login").click(function(){
		if ($("input[name=loginemail]").val()=="")
		{
			$("#loginerror").text("Please enter your email");
		}
		else if ($("input[name=loginpass]").val()=="")
		{
			$("#loginerror").text("Please enter your password");
		}
		else
		{
			$.ajax({
				url:'login.php',
				type: 'POST',
				data:{
					password: $("input[name=loginpass]").val(),
					email: $("input[name=loginemail]").val()
				},
				success: function(response)
				{
					if (response=="SUCCESS")
						window.location.replace("index.php");
					else if(response=="NOCOURSES")
					    window.location.replace("mycourses.php");
					else
						$('#loginerror').html(response);
				}
			});
		}
	}
	);
	
	<?php
		//If user exits a field without filling it out, alert them!
	?>
	
	$("input[name=firstname]").blur(function(){
		if ($("input[name=firstname]").val()=="")
			$("#name-error").text("Please enter your name!");
		else
			$("#name-error").text("");
	});
	
	$("input[name=lastname]").blur(function(){
		if ($("input[name=lastname]").val()=="")
			$("#name-error").text("Please enter your name!");
		else
			$("#name-error").text("");
	});
	
	$("input[name=password]").blur(function(){
		if ($("input[name=password]").val()=="")
			$("#password-error").text("Please enter a password");
		else
			$("#password-error").text("");
	});
	
	$("input[name=confirmation]").blur(function(){
		if ($("input[name=confirmation]").val()=="" || $("input[name=confirmation]").val()!=$("input[name=password]").val())
			$("#confirmation-error").text("Your confirmation password does not match!");
		else
			$("#confirmation-error").text("");
	});
	
	$("input[name=email]").blur(function(){
		var re=/.+@(.+\.|)harvard\.edu$/;
		if (!$("input[name=email]").val().match(re))
			$("#email-error").text("Your email address does not seem to be a valid harvard.edu email.");
		else
		$("#email-error").text("");
	});
	
	$("#submit").click(function(){
		$.ajax({
			url:'register.php',
			type: 'POST',
			data:{
				firstname: $("input[name=firstname]").val(),
				lastname: $("input[name=lastname]").val(),
				password: $("input[name=password]").val(),
				confirmation: $("input[name=confirmation]").val(),
				email: $("input[name=email]").val()
			},
			success: function(response){
				$('#response').html(response);
			}
		});
		return false;
	});
});
</script>
