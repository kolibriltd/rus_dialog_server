<?php
    $json_text_decode = json_decode($model->path_txt_file);
    $floorId = 0;
    if (isset($_GET['branch'])) {
        $branchId = $_GET['branch'];
        if ($branchId == 0) {
            $floorId = 0;
        }
        if ($branchId >= 1 && $branchId <= 2) {
            $floorId = 1;
        }
        if ($branchId > 2 && $branchId < 7) {
            $floorId = 2;
        }
    }
    else {
        $branchId = 0;
    }
    //print_r($json_text_decode);
?>
<div  id="book_right">
    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/books/update/id/<?php echo $_GET['id']?>">Этап 1</a>
    <a href="">Этап 2</a>
</div>
<div id="content_book">
    <div id="book_left">
        <?php echo " ".$json_text_decode->result[0]->nameA.": *персонаж управляемый читателем";?>
        <div>
            <button id="peopleA">ТЕКСТ</button>
            <button id="imgPeopleA">ФОТО</button>
            <button id="audioPeopleA">АУДИО ФАЙЛ</button>
        </div>
        <br/>
        <?php echo " ".$json_text_decode->result[1]->nameB.":";?>
        <div>
            <button id="peopleB">ТЕКСТ</button>
            <button id="imgPeopleB">ФОТО</button>
            <button id="audioPeopleB">АУДИО ФАЙЛ</button>
        </div>
        <br/>
        <div>
            <button id="contextBtn">СЛОВА АВТОРА</button>
            <button id="branchBtn">ДОБАВИТЬ ВЕТКУ</button>
        </div>
        <div id="conteiner_add_book">
            <input size="60" name="peopleA" type="text"/>
            <div id="uploadFile">
<?php $this->widget('ext.EAjaxUpload.EAjaxUpload',
array(
        'id'=>'path',
        'config'=>array(
               'action' => Yii::app()->createUrl('books/upload/id/' . $_GET['id']),
               'allowedExtensions' => array("jpg", "jpeg", "mp3"),//array("jpg","jpeg","gif","exe","mov" and etc...
               'sizeLimit' => 20*1024*1024,// maximum file size in bytes
               'multiple' => false,
               'onComplete' => "js:function(id, fileName, responseJSON){ nextUpload(responseJSON); }",
              )
)); ?>
            </div>
            <input size="60" name="peopleB" type="text"/>
            <div id="context_blok_add">
                <div>
                    <button id="context">СЛОВА</button>
                    <button id="collBtn">ЗВОНОК</button>
                </div>
                <div id="contextTextArea">
                    <textarea rows="6" cols="40" name="context" id="Books_description"></textarea>
                </div>
                <div id="collBtnSelect">
                    <button id="callPeopleB">Исходящий | Сброшен</button>
                    <button id="missCallPeopleB">Исходящий | Не отвечает</button>
                </div>
            </div>
            <div id="branch_blok_add">
                <div class="row">
                    <label for="branchOne" class="required">Ответ первый:</label>
                    <input size="60" name="branchOne" id="branchOne" type="text" />    </div>

                <div class="row">
                    <label for="branchTwo" class="required">Ответ второй:</label>
                    <input size="60" name="branchTwo" id="branchTwo" type="text" />
                </div>
            </div>
        </div>
        <button id="send" typeSend="text" btn="peopleA">ОТПРВИТЬ</button>
    </div>

    <div id="book_right">
        <div>
            <a href="?branch=0">Голова</a>
<?php
foreach ($json_text_decode->result as $value) {
    if (isset($value->branch)) {
?>
            <a href="?branch=1">Ветка 1</a>
            <a href="?branch=2">Ветка 2</a>
<?php
            foreach ($value->branch[0]->content as $valueBr) {
                if (isset($valueBr->branch)) {
?>
            <a href="?branch=3">Ветка 3</a>
            <a href="?branch=4">Ветка 4</a>
<?php
                }
            }
            foreach ($value->branch[1]->content as $valueBr) {
                if (isset($valueBr->branch)) {
?>
            <a href="?branch=5">Ветка 5</a>
            <a href="?branch=6">Ветка 6</a>
<?php
                }
            }
    }
}
?>
            </div>
        <div id="book_message">
<?php
        bookMessage($json_text_decode->result, $json_text_decode->result[0]->nameA, $json_text_decode->result[1]->nameB, 0, $branchId, false, $model->dir_book);
?>
        </div>
    </div>
</div>

<script>
    var floorId = <?php echo $floorId?>;
    var height = $('#book_message')[0].scrollHeight;
    var file = "";
    function nextUpload(responseJSON) {
        file = responseJSON.filename;
    }
    $('#book_message').scrollTop(height);
    $("input[type=text][name=peopleA]").show();
    $("input[type=text][name=peopleB]").hide();
    $("#uploadFile").hide();
    $("div#editMgs").hide();
    $("#context_blok_add").hide();
    $("#branch_blok_add").hide();
    $("#peopleA").click(function(){
        $("input[type=text][name=peopleA]").show();
        $("input[type=text][name=peopleB]").hide();
        $("#uploadFile").hide();
        $("#branch_blok_add").hide();
        $("#context_blok_add").hide();
        $('#send').attr("typeSend", "text");
        $('#send').attr("btn", "peopleA");
    });
    $("#imgPeopleA").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").show();
        $("#context_blok_add").hide();
        $("#branch_blok_add").hide();
        $("#send").attr("typeSend", "file");
        $('#send').attr("btn", "imgPeopleA");
    });
    $("#audioPeopleA").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").show();
        $("#context_blok_add").hide();
        $("#branch_blok_add").hide();
        $("#send").attr("typeSend", "file");
        $('#send').attr("btn", "audioPeopleA");
    });
    $("#peopleB").click(function(){
        $("input[type=text][name=peopleB]").show();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").hide();
        $("#branch_blok_add").hide();
        $("#context_blok_add").hide();
        $('#send').attr("typeSend", "text");
        $('#send').attr("btn", "peopleB");
    });
    $("#imgPeopleB").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").show();
        $("#context_blok_add").hide();
        $("#branch_blok_add").hide();
        $("#send").attr("typeSend", "file");
        $('#send').attr("btn", "imgPeopleB");
    });
    $("#audioPeopleB").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").show();
        $("#context_blok_add").hide();
        $("#branch_blok_add").hide();
        $("#send").attr("typeSend", "file");
        $('#send').attr("btn", "audioPeopleB");
    });
    $("#contextBtn").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").hide();
        $("#branch_blok_add").hide();
        $("#context_blok_add").show();
        $("#contextTextArea").show();
        $("#collBtnSelect").hide();
        $('#send').attr("typeSend", "context");
        $('#send').attr("btn", "context");
    });
    $("#context").click(function(){
        $("#contextTextArea").show();
        $("#collBtnSelect").hide();
        $('#send').attr("typeSend", "context");
        $('#send').attr("btn", "context");
    });
    $("#collBtn").click(function(){
        $("#contextTextArea").hide();
        $("#collBtnSelect").show();
    });
    $("#callPeopleB").click(function(){
        $('#send').attr("typeSend", "callPeopleB");
        $('#send').attr("btn", "callPeopleB");
    });
    $("#missCallPeopleB").click(function(){
        $('#send').attr("typeSend", "missCallPeopleB");
        $('#send').attr("btn", "missCallPeopleB");
    });
    $("#branchBtn").click(function(){
        $("input[type=text][name=peopleB]").hide();
        $("input[type=text][name=peopleA]").hide();
        $("#uploadFile").hide();
        $("#context_blok_add").hide();
        $("#branch_blok_add").show();
        $('#send').attr("typeSend", "branch");
        $('#send').attr("btn", "branch");
    });
    $("#send").click(function(){
        var btn = $('#send').attr("btn");
        var type = $('#send').attr("typeSend");
        var url = "http://www.wearesputnik.com<?php echo Yii::app()->request->baseUrl; ?>/index.php/books/addtextbook/id/<?php echo $_GET['id']?>/type/" + btn + "/branch/<?php echo $branchId;?>/floor/" + floorId;
        if (type.toString() === "text") {
            $.post(url, {text : $("input[type=text][name=" + btn + "]").val()}, function( data ) {
                $("input[type=text][name=" + btn + "]").val("");
                $("#book_message").append(data);
            });
        }
        if (type.toString() === "context") {
            $.post(url, {text : $("textarea[name=context]").val()}, function( data ) {
                $("textarea[name=context]").val("");
                $("#book_message").append(data);
            });
        }
        if (type.toString() === "callPeopleB") {
            $.post(url, {text : "callPeopleB"}, function( data ) {
                $("#book_message").append(data);
            });
        }
        if (type.toString() === "missCallPeopleB") {
            $.post(url, {text : "missCallPeopleB"}, function( data ) {
                $("#book_message").append(data);
            });
        }
        if (type.toString() === "branch") {
           $.post(url, {branchOne : $("input[type=text][name=branchOne]").val(), 
                branchTwo: $("input[type=text][name=branchTwo]").val() }, function( data ) {
                $("#book_message").append(data);
                floorId++;
            }); 
        }
        if (type.toString() === "file") {
            $.post(url, {text : file}, function( data ) {
                $("#book_message").append(data);
            });
            $(".qq-upload-list").html("");
        }
        var height = $('#book_message')[0].scrollHeight;
        $('#book_message').scrollTop(height);
    });
    
    $(".messageClass").click(function(){
        $("div#editMgs").hide();
        $(this).children("#editMgs").show();
    });
    $("a#deleteHref").click(function(){
        var floorMsg = $(this).attr("floor");
        var keyMsg = $(this).attr("keyMgs");
        var url = "http://www.wearesputnik.com<?php echo Yii::app()->request->baseUrl; ?>/index.php/books/deletemessagetext/id/<?php echo $_GET['id']?>/branch/<?php echo $branchId;?>/floorMsg/" + floorMsg + "/keyMsg/" + keyMsg;
        $.get(url, function(data){
            if (data == "1") {
                $("#message" + floorMsg + keyMsg).hide();
            }
        });
        
    });
</script>

<?php
function bookMessage($json_text_decode, $nameA, $nameB, $floor, $branch, $twoBranch, $dir_book) {
    foreach ($json_text_decode as $key => $value) {
        if (isset($value->peopleA) || isset($value->imgPeopleA) || isset($value->audioPeopleA)) {
?>
        <div id="message<?php echo $floor.$key;?>">
            <div class="messageClass" id="peopleA_blok">
                <div id="editMgs">
                    <a href="">Edit</a>
                    <a floor="<?php echo $floor;?>" keyMgs="<?php echo $key;?>" id="deleteHref" href="#delete">Delete</a>
                </div>
                <div id="peopleA_msg">
<?php
            if (isset($value->peopleA)) { 
?>
                    <?php echo $value->peopleA;?>
<?php
            }
            if (isset($value->imgPeopleA)) {
                if (!empty($dir_book) && file_exists(Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->imgPeopleA)) {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->imgPeopleA;?>" width="200"/>
<?php                    
                }
                else {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->imgPeopleA;?>" width="200"/>
<?php
                }
            }
            if (isset($value->audioPeopleA)) {
                if (!empty($dir_book) && file_exists(Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleA)) {
?>
                    <audio controls>
<!--                        <source src="audio/music.ogg" type="audio/ogg; codecs=vorbis">-->
                        <source src="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleA;?>" type="audio/mpeg">
                        Тег audio не поддерживается вашим браузером. 
                        <a href="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleA;?>">Скачайте музыку</a>.
                    </audio>
                    
<?php
                }
                else {
?>
                    <audio controls>
<!--                        <source src="audio/music.ogg" type="audio/ogg; codecs=vorbis">-->
                        <source src="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->audioPeopleA;?>" type="audio/mpeg">
                        Тег audio не поддерживается вашим браузером. 
                        <a href="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->audioPeopleA;?>">Скачайте музыку</a>.
                    </audio>
                    
<?php
                }
            }
?>
                    </div>
                <div id="peopleA_name">
                    <?php echo $nameA;?>
                </div>
            </div>
        </div>
<?php
        }
        if (isset($value->peopleB) || isset($value->imgPeopleB) || isset($value->audioPeopleB)) {
?>
        <div id="message<?php echo $floor.$key;?>">
            <div class="messageClass" id="peopleB_blok">
                <div id="editMgs">
                    <a href="">Edit</a>
                    <a floor="<?php echo $floor;?>" keyMgs="<?php echo $key;?>" id="deleteHref" href="#delete">Delete</a>
                </div>
                <div id="peopleB_msg">
<?php
            if (isset($value->peopleB)) {
?>
                    <?php echo $value->peopleB;?>
<?php
            }
            if (isset($value->imgPeopleB)) { 
                if (!empty($dir_book) && file_exists(Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->imgPeopleB)) {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->imgPeopleB;?>" width="200"/>
<?php                    
                }
                else {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->imgPeopleB;?>" width="200"/>
<?php
                }
            }
            if (isset($value->audioPeopleB)) { 
                if (!empty($dir_book) && file_exists(Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleB)) {
?>
                    <audio controls>
<!--                        <source src="audio/music.ogg" type="audio/ogg; codecs=vorbis">-->
                        <source src="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleB;?>" type="audio/mpeg">
                        Тег audio не поддерживается вашим браузером. 
                        <a href="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $dir_book . "/" . $value->audioPeopleB;?>">Скачайте музыку</a>
                    </audio>
<?php
                }
                else {
?>
                    <audio controls>
<!--                        <source src="audio/music.ogg" type="audio/ogg; codecs=vorbis">-->
                        <source src="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->audioPeopleB;?>" type="audio/mpeg">
                        Тег audio не поддерживается вашим браузером. 
                        <a href="<?php echo Yii::app()->request->baseUrl . "/protected" . $value->audioPeopleB;?>">Скачайте музыку</a>
                    </audio>
<?php
                }
            }
?>
                    </div>
                <div id="peopleB_name">
                    <?php echo $nameB;?>
                </div>
            </div>
        </div>
<?php
        }
        if (isset($value->context) || isset($value->callPeopleB) || isset($value->missCallPeopleB)) {
?>
        <div id="message<?php echo $floor.$key;?>">  
            <div class="messageClass" id="context_blok">
                <div id="editMgs">
                    <a href="">Edit</a>
                    <a floor="<?php echo $floor;?>" keyMgs="<?php echo $key;?>" id="deleteHref" href="#delete">Delete</a>
                </div>
<?php
            if (isset($value->context)) {
?>
                <?php echo $value->context;?>
<?php
            }
            if (isset($value->callPeopleB)) {
?>
                <?php echo $nameB . " сбросил(-а) вызов";?>
<?php
            }
            if (isset($value->missCallPeopleB)) {
?>
                Абонент не отвечает
<?php
            }
?>
            </div>
        </div>
<?php
        }
        if (isset($value->branch) && $branch != 0) {
            $floor++;
            if (($branch == 1 || $branch == 2) && !$twoBranch) {
?>
 
        <div>
            Ветка <?php echo numBranch($branch, $twoBranch);?>
        </div>
        
<?php
            }
            if ($branch > 2 && $branch < 7) {
?>
 
        <div>
            Ветка <?php echo numBranch($branch, $twoBranch);?>
        </div>
        
<?php                
            }
            if ($branch == 3 || $branch == 4) {
                if (!$twoBranch) {
                    bookMessage($value->branch[0]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                }
                else {
                    if ($branch == 3) {
                        bookMessage($value->branch[0]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                    }
                    if ($branch == 4) {
                        bookMessage($value->branch[1]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                    }
                }
            }
            if ($branch == 5 || $branch == 6) {
                if (!$twoBranch) {
                    bookMessage($value->branch[1]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                }
                else {
                    if ($branch == 5) {
                        bookMessage($value->branch[0]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                    }
                    if ($branch == 6) {
                        bookMessage($value->branch[1]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
                    }
                }
            }
            if ($branch == 1 && !$twoBranch) {
                bookMessage($value->branch[0]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
            }
            if ($branch == 2 && !$twoBranch) {
                bookMessage($value->branch[1]->content, $nameA, $nameB, $floor, $branch, true, $dir_book);
            }
        }
    }
}

function numBranch($branch, $twoBranch) {
    if ($twoBranch) {
        if ($branch == 1) {
            return 3;
        }
        if ($branch == 2) {
            return 5;
        }
        if ($branch > 2) {
            return $branch;
        }
    }
    else {
        if ($branch == 3) {
            return 1;
        }
        if ($branch == 4) {
            return 1;
        }
         if ($branch == 5) {
            return 2;
        }
        if ($branch == 6) {
            return 2;
        }
        return $branch;
    }
}
?>