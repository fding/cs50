
<form action="register.php" method="post">
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
        <div class="control-group">
            <button type="submit" class="btn">Register</button>
        </div>
    </fieldset>
</form>
<script>
$(document).ready( function(){
    $("form").submit(function(){
        if ($("input[name=firstname]").val()=="")
        {
            return false;
        }
        return true;
        }
    );
    $("input[name=firstname]").blur(function(){
        if ($("input[name=firstname]").val()=="")
        {
        $("#name-error").text("Please enter your name!");
        }
        else
        $("#name-error").text("");
    }
    );
    
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
})
</script>
<div>
    or <a href="register.php">log in</a>.
</div>
