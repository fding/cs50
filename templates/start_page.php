
<form id = "registration">
	<fieldset class="controls controls-row">
	    <legend><strong>New here?</strong> Sign up!</legend>
			<input name="firstname" placeholder="First Name" type="text" style="width:100px"/>
			<input name="lastname" placeholder="Last Name" type="text" style="width:100px"/><br>
			<input name="password" placeholder="Password" type="password" style="width:218px"/><br>
			<input name="confirmation" placeholder="Password Confirmation" type="password" style="width:218px"/><br>			
			<input name="email" placeholder="Harvard College Email" type="text" style="width:218px"/><br>
            <button class="btn btn-warning" id="submit" style="float:right">Register</button>
	</fieldset>

</form>
<form id = "login"> 
    <fieldset class="control control-row">
            <input name="loginemail" placeholder="Harvard College Email" type="text" style="width:218px"/><br>
            <input name="loginpass" placeholder="Password" type="password" style="width:140px"/>
            <button class="btn btn-medium btn-info" id="loginbutton" style="float:right">Sign in</button>
    </fieldset>
</form>
<div id = "welcome-header">
<h1>CrimsonDiscuss.</h1>
</div>

<div id = "welcome-info">
<h4>Revolutionizing the Harvard educational experience. Find out what’s happening, right now, with the students and psets you care about.</h4>
</div>
<script>
$(document).ready( function(){
	$("#loginbutton").click(function(){
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
					password: $("input[name='loginpass']").val(),
					email: $("input[name='loginemail']").val()
				},
				success: function(response)
				{
					if (response=="SUCCESS")
						window.location.replace("index.php");
				}
			});
		}
		return false;
	}
	);
	<?php
		//If user exits a field without filling it out, alert them!
	?>
	
	$("input[name=firstname]").blur(function(){
		if ($("input[name=firstname]").val()=="")
			$("#error").text("Please enter your name!");
		else
			$("#error").text("");
	});
	
	$("input[name=lastname]").blur(function(){
		if ($("input[name=lastname]").val()=="")
			$("#error").text("Please enter your name!");
		else
			$("#error").text("");
	});
	
	$("input[name=password]").blur(function(){
		if ($("input[name=password]").val()=="")
			$("#error").text("Please enter a password");
		else
			$("#error").text("");
	});
	
	$("input[name=confirmation]").blur(function(){
		if ($("input[name=confirmation]").val()=="" || $("input[name=confirmation]").val()!=$("input[name=password]").val())
			$("#error").text("Your confirmation password does not match!");
		else
			$("#error").text("");
	});
	
	$("input[name=email]").blur(function(){
		var re=/.+@(.+\.|)harvard\.edu$/;
		if (!$("input[name=email]").val().match(re))
			$("#error").text("Invalid harvard.edu email.");
		else
		$("#error").text("");
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
