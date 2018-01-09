<?php
/* @var $this BooksController */
/* @var $model Books */
$json_text_decode = json_decode($model->path_txt_file);
$nameA = "";
$nameB = "";
if (isset($json_text_decode->result[0]->nameA)) {
    $nameA = $json_text_decode->result[0]->nameA;
}
if (isset($json_text_decode->result[1]->nameB)) {
    $nameB = $json_text_decode->result[1]->nameB;
}


/*
?>

<h1>Update Books <?php echo $model->id_book; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, "typeList" => $typeList)); */?>
<div  id="book_right">
    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/books/update/id/<?php echo $_GET['id']?>">Этап 1</a>
    <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/books/createtext/id/<?php echo $_GET['id']?>">Этап 2</a>
</div>
<div id="content_book">
    <div class="form" id="book_left">

    <?php echo CHtml::form('','post',array('enctype'=>'text/form-data')); ?>

        <div class="row">
            <label for="Books_name" class="required">Название: <span class="required">*</span></label>
            <?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
        </div>

        <div class="row">
            <label for="Books_author" class="required">Автор: <span class="required">*</span></label>
            <?php echo CHtml::activeTextField($model,'author',array('size'=>60,'maxlength'=>100)); ?>
        </div>

        <div class="row">
            <label for="Books_peopleA" class="required">Персанаж А: <span class="required">*</span></label>
            <input size="60" maxlength="100" name="Books[peopleA]" id="Books_peopleA" type="text" value="<?php echo $nameA;?>"/>
        </div>

        <div class="row">
            <label for="Books_peopleB" class="required">Персанаж Б: <span class="required">*</span></label>
            <input size="60" maxlength="100" name="Books[peopleB]" id="Books_peopleB" type="text" value="<?php echo $nameB;?>"/>
        </div>

        <div class="row">
            <label for="Books_description" class="required">Описание: <span class="required">*</span></label>
            <?php echo CHtml::activeTextArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton('Save'); ?>
        </div>

    <?php echo CHtml::endForm(); ?>

    </div>
    <div  id="book_right">
<?php
        if (!empty($model->path_cover_file)) {
            if (!empty($model->dir_book) && file_exists(Yii::app()->request->baseUrl . "/uploads/" . $model->dir_book . "/" . $model->path_cover_file)) {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/uploads/" . $model->dir_book . "/" .$model->path_cover_file;?>" width="300"/>
<?php           
            }
            else {
?>
                    <img src="<?php echo Yii::app()->request->baseUrl . "/protected" . $model->path_cover_file;?>" width="300"/>
<?php                
            }
        }
?>
    </div>
</div>