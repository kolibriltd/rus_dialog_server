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
?>

<h1><?php echo $model->name; ?></h1>


<div id="content_book">
    <div id="book_left">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'author',
		'description',
	),
)); ?>
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
<?php
function bookMessage($json_text_decode, $nameA, $nameB, $floor, $branch, $twoBranch, $dir_book) {
    foreach ($json_text_decode as $key => $value) {
        if (isset($value->peopleA) || isset($value->imgPeopleA) || isset($value->audioPeopleA)) {
?>
        <div id="message<?php echo $floor.$key;?>">
            <div class="messageClass" id="peopleA_blok">
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