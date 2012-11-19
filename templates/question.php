<link rel="stylesheet" type="text/css" href="/css/bootstrap-wysihtml5.css"></link>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css"></link>

<script src="js/wysihtml5-0.3.0.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-wysihtml5.js"></script>
<script src="js/jquery.cookie.js"></script>

<div id="askModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Ask</h3>
    </div>
    <div class="modal-body">
    <div id="response">
    </div>
        <textarea id="question" placeholder="Post your question here..." style="width: 510px; height: 150px"></textarea>
        <textarea id="tags" placeholder="Enter tags here..." style="width: 510px; height: 20px"></textarea>
        <script type="text/javascript">
        $('#question').wysihtml5();
        </script>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" id="submit">Submit</button>
    </div>
</div>
