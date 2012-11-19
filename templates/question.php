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
        <div class="control-group">
            <select name="symbol">
                <option value="">Tags</option>
                <option value=pset>pset</option>
                <option value=essay>essay</option>
            </select>
            <input id="psetnum" type="text" style="width: 20px"></input>
            <select name="symbol">
                <option value="">Courses</option>
                <option value=1>Math 55</option>
                <option value=essay>CS 50</option>
                <option value=phys>Physics 16</option>
            </select>
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
