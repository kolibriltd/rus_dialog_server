<?php
$this->menu=array(
	array('label'=>'Manage Books', 'url'=>array('admin')),
);
?>
<h1>New Push</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
            <label for="OneSignal_title" class="required">Название пуша <span class="required">*</span></label>
            <input size="60" maxlength="100" name="OneSignal[title]" id="OneSignal_title" type="text" />
	</div>

	<div class="row">
            <label for="OneSignal_message" class="required">Сообщение пуша <span class="required">*</span></label>
            <input size="60" maxlength="100" name="OneSignal[message]" id="OneSignal_message" type="text" />
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Push'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>