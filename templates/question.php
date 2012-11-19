
<div id="askModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Ask</h3>
    </div>
    <div class="modal-body">
    <div id="response">
    </div>
        <input id="title" placeholder="Title..." type="text" style="width: 510px"></input>
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
                <?php $file = fopen("../includes/courses.json", "r"); ?>
                
                <?php $json = stream_get_contents($file); ?>
                <?php $courses = json_decode($json, true); ?>                
                <?php
                    foreach ($courses as $key => $value) 
                        print("<option value={$value}>{$key}</option>");
                ?>
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
