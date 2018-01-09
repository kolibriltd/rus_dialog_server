<?php
/* @var $this BooksController */
/* @var $model Books */

$this->menu=array(
	array('label'=>'Create Books', 'url'=>array('create')),
        array('label'=>'New Push', 'url'=>array('newpush')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#books-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Books</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php //new OneSignal("Появилась новая история") ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'books-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		'author',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
