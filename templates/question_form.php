<div id="askModal" class="modal hide fade"   tabindex="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="myModalLabel">Ask</h3>
    </div>
    <div class="modal-body">
        <div id="response">
        </div>
        <input id="title" name="title" placeholder="Title..." type="text" style="width: 600px" /><br/>
        <textarea id="question" name="question" placeholder="Detail your question. To input equations, enclose LaTeX as in: $$expression$$." style="width: 600px; height: 220px"></textarea>
        <div class="control-group">
<input name="type" type="text" data-provide="typeahead" style="width: 100px" data-minLength="0" data-source='["pset","project","final","lab","midterm","essay","lecture","section","reading"]' />

<?php /*
            <select name="type" style="width:100px">
                <option value="">Tags</option>
                <option value=pset>pset</option>
                <option value=essay>essay</option>
            </select>*/?>
            <input id="psetnum" name="psetnum" type="text" style="width: 20px; " value=
            <?php 
                if (!empty($selectedtags))
                {
                    $number=filter_var($tags[$selectedcoursesid[0]][$selectedtags[0]]["tag_name"],FILTER_SANITIZE_NUMBER_INT);
                    print("\"".$number."\"");
                }
                else print ("\"\"");
            ?>/>
            <select name="courselist" style="width:200px; margin-left:20px;">
                <option value="">Courses</option>
            </select>
            <select name="privacy">
                <option value="0">Visible to all</option>
                <option value="1">Post anonymously</option>
                <option value="3">Visible only to certain friends</option>
            </select>
        <input id="visibleto" name="visibleto" placeholder="Visible to ..." type="text" style="width: 600px; display:none;" />
        
           
            <span id="submit-error" style="color:red"></span>
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
