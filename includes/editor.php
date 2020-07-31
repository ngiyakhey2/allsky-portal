<?php

function DisplayEditor()
{

    $status = new StatusMessages();

    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            var editor = null;

            $.get("current/config.sh", function (data) {
                editor = CodeMirror(document.querySelector("#editorContainer"), {
                    value: data,
                    mode: "shell",
                    theme: "monokai"
                });
            });

            $("#save_file").click(function (){
                editor.display.input.blur();
                var content = editor.doc.getValue(); //textarea text
                var path = $("#script_path").val(); //path of the file to save
                var response = confirm("Do you want to save your changes?");
                if(response)
                {
                    $.ajax({
                        type: "POST",
                        url: "includes/save_file.php",
                        data: {content:content, path:path},
                        dataType: 'text',
                        success: function(){
                            //alert("File saved!");
                        }
                    });
                }
                else{
                    //alert("File not saved!");
                }
            });
        });

        function onFileChange(e) {
            $("#editorContainer").html("");
            $.get(e.value, function (data) {
                CodeMirror(document.querySelector("#editorContainer"), {
                    value: data,
                    mode: "shell",
                    theme: "monokai"
                });
            });
        }

    </script>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><i class="fa fa-code fa-fw"></i> Script Editor</div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <p><?php $status->showMessages(); ?></p>
                        <div id="editorContainer"></div>
                        <div style="margin-top: 15px;">
                            <select class="form-control" id="script_path"
                                    style="display: inline-block; width: auto; margin-right: 15px; margin-bottom: 5px"
                                    onchange="onFileChange(this)">
                                <option value="current/config.sh">config.sh</option>
                                <?php
                                $path = '/home/pi/allsky/scripts';
                                $scripts = array_diff(scandir($path), array('.', '..'));

                                foreach ($scripts as $script) {
                                    echo "<option value='current/scripts/$script'>$script</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-success" style="margin-bottom:5px" id="save_file"/>
                            <i class="fa fa-save"></i> Save Changes</button>
                            <button class="btn btn-danger" style="margin-bottom:5px"/>
                            <i class="fa fa-times"></i> Cancel</button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

?>
