
<div class="form">

<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>


	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'type_id'); ?>
		<?php echo CHtml::dropDownList("Books[type_id]", $model->type_id, CHtml::listData($typeList, 'type_id', 'name'), array('empty' => 'Выбирите категорию')); ?>
		<?php echo CHtml::error($model,'type_id'); ?>
	</div>
        
        <div class="row">
		<?php echo CHtml::activeLabelEx($model,'name'); ?>
		<?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo CHtml::error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'author'); ?>
		<?php echo CHtml::activeTextField($model,'author',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo CHtml::error($model,'author'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'txt_file'); ?>
		<?php echo CHtml::activeFileField($model,'txt_file',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo CHtml::error($model,'txt_file'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'cover_file'); ?>
		<?php echo CHtml::activeFileField($model,'cover_file',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo CHtml::error($model,'cover_file'); ?>
	</div>
        
        <div class="row">
		<?php echo CHtml::activeLabelEx($model,'description'); ?>
		<?php echo CHtml::activeTextArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo CHtml::error($model,'description'); ?>
	</div>
        
        <div class="row">
		<?php echo CHtml::activeLabelEx($model,'guest'); ?>
		<?php echo CHtml::activeCheckBox($model,'guest'); ?>
		<?php echo CHtml::error($model,'guest'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->