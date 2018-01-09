<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Edit"),
);
?>

<div class="two_column">
<?php echo $this->renderPartial('menu'); ?>
<div class="right_part">

<h1 class="no_mt"><?php echo UserModule::t('Edit profile'); ?></h1>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>
<div class="form">
<?php $form=$this->beginWidget('UActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo UserModule::t('Поля помеченные <span class="required">*</span> обязательны для заполнения'); ?></p>

	<?php echo $form->errorSummary(array($model,$profile)); ?>

<?php 
		$profileFields=$profile->getFields();
		if ($profileFields) {
			foreach($profileFields as $field) {
                            if ($field->user_visible == 0 OR $profile['user_right'] == $field->user_visible){
                            ?>
	<div class="input_str">
		<?php echo $form->labelEx($profile,$field->varname);
		
		if ($field->widgetEdit($profile)) {
			echo $field->widgetEdit($profile);
		} elseif ($field->range) {
			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
		} elseif ($field->field_type=="TEXT") {
			echo $form->textArea($profile,$field->varname,array('class'=>'input_long'));
		} else {
			echo $form->textField($profile,$field->varname,array('class'=>'input_long'));
		}
		echo $form->error($profile,$field->varname); ?>
	</div>	
			<?php
                            }
			}
		}
?>
	<div class="input_str">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('class'=>'input_long')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="input_str">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('class'=>'input_long')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class'=>'submit')); ?>
	</div>

<?php $this->endWidget(); ?>


</div><!-- form -->
</div>
</div>
