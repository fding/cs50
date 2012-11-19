<div style="text-align:center;">
<h4>
Hello, <?=$user["firstname"]?>! Please tell us what courses you are taking.
</h4>
<form action="addcourses.php" method="post">
    <div id="addedcourses">
        <input type="text" data-id="1" class="coursename" data-provide="typeahead" placeholder="Search for classes" value="">
        <button class="btn btn-success addcoursebutton" data-id="1" type="button">Add</button>
        <input type="hidden" name="course1" value="">
    </div>
    <button type="submit" class="btn">Finish</button>
</form>
</div>
<script>
    $(document).ready(function(){
        window.buttons=1;
        //$('.addcoursebutton').hide();
        $("#addedcourses").on("click", ".addcoursebutton", function(){
            $("input[name='course"+$(this).data("id")+"']").val($("input[data-id='"+$(this).data("id")+"']").val());
            
            if (window.buttons==$(this).data("id"))
            {
                createElement(window.buttons+1);
                window.buttons+=1;
            }
        }
        );
        /*
        $("#addedcourses").on("change",".coursename", function(){
            if ($(this).val()=="")
                $("button[data-id='"+$(this).data("id")+"']").show();
            else
                $("button[data-id='"+$(this).data("id")+"']").show();   
        }
        );*/
    }
    );
    
    function createElement(number)
    {
        firstinput="<input type='text' class='coursename' data-id='"+number.toString()+"' data-provide='typeahead' placeholder='Search for classes'>\n";
        secondinput="<button class='btn btn-success addcoursebutton' data-id='"+number.toString()+"' type='button' style='visibility:invisible'>Add</button>\n";
        thirdinput="<input type='hidden' name='course"+number.toString()+"' value=''>";
        $("#addedcourses").append("<br/\>"+firstinput+secondinput+thirdinput);
        $("#button"+number.toString()).bind("click",
            function(){
                $("input[name='course"+number.toString()+"']").val($("#course"+number.toString()).val());
                if (window.buttons==number.toString())
                {
                    createElement(window.buttons+1);
                }
        }
        );
    }
</script>
