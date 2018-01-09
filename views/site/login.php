<?php $model = new UserLogin;?>
<div id="left_content">
    
</div>
<div id="right_content">
    <div class="form">
<?php echo CHtml::beginForm(Yii::app()->request->baseUrl . "/index.php/user/login") ?>
        <?php echo CHtml::errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::activeTextField($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activePasswordField($model,'password'); ?>
	</div>

	<div class="row rememberMe">
		<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

    <?php echo CHtml::endForm(); ?>
    </div><!-- form -->
</div>


