<div id="feedbackModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel">Feedback</h3>
	</div>
	<div class="modal-body">
	    We'd love to hear what you think about our website! Is it totally awesome? Are there bugs that you want to report?
	    Features that you would like? Your reply is completely anonymous.
		<div id="response">
		</div>
		<form>
			<fieldset>
				<textarea id="feedbackbody" style="width:96%; height:200px;"></textarea>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary" id="submitfeedback">Submit</button>
	</div>
</div>
<?php
	// Now add event handlers and client-side error checking code
?>
<script>
$(document).ready( function(){
	$("#submitfeedback").click(function(){
		$.ajax({
			url:'submitfeedback.php',
			type: 'POST',
			data:{
				feedback:$("#feedbackbody").val()
			},
			success: function(response){
				$.showmsg("Your feedback has been recorded. Thanks!");
			    $("#feedbackModal").modal('hide');
			    return true;
			}
		});
	});
});
</script>
