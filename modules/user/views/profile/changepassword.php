<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Change Password");
$this->breadcrumbs=array(
	UserModule::t("Profile") => array('/user/profile'),
	UserModule::t("Change Password"),
);
?>

<div class="two_column">
<?php echo $this->renderPartial('menu'); ?>
<div class="right_part">

<h1 class="no_mt"><?php echo UserModule::t('Change password'); ?></h1>

<div class="form">
<?php $form=$this->beginWidget('UActiveForm', array(
	'id'=>'changepassword-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note"><?php echo UserModule::t('Поля помеченные <span class="required">*</span> обязательны для заполнения'); ?></p>
	<?php echo CHtml::errorSummary($model); ?>
	
	<div class="input_str">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password',array('class'=>'input_short')); ?>
	<?php echo $form->error($model,'password'); ?>
	<p class="hint">
	<?php echo UserModule::t("Minimal password length 4 symbols."); ?>
	</p>
	</div>
	
	<div class="input_str">
	<?php echo $form->labelEx($model,'verifyPassword'); ?>
	<?php echo $form->passwordField($model,'verifyPassword',array('class'=>'input_short')); ?>
	<?php echo $form->error($model,'verifyPassword'); ?>
	</div>
	
	
	<div class="input_str">
	<?php echo CHtml::submitButton('Изменить',array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
</div>
</div>