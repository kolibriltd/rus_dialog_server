<div class="form">

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
        <input size="60" maxlength="100" name="Books[peopleA]" id="Books_peopleA" type="text" />
    </div>
    
    <div class="row">
        <label for="Books_peopleB" class="required">Персанаж Б: <span class="required">*</span></label>
        <input size="60" maxlength="100" name="Books[peopleB]" id="Books_peopleB" type="text" />
    </div>
    
    <div class="row">
        <label for="Books_description" class="required">Описание: <span class="required">*</span></label>
        <?php echo CHtml::activeTextArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
    </div>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php echo CHtml::endForm(); ?>

</div>