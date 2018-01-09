<?php
/* @var $this BooksController */
/* @var $data Books */
?>
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_book')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_book), array('view', 'id'=>$data->id_book)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
	<?php echo CHtml::encode($data->author); ?>
	<br />
        
	<b><?php echo CHtml::encode($data->getAttributeLabel('path_cover_file')); ?>:</b>
	<?php echo CHtml::encode($data->path_cover_file); ?>
	<br />


</div>