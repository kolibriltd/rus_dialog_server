<?php
/* @var $this BooksController */
/* @var $model Books */

$this->menu=array(
	array('label'=>'Manage Books', 'url'=>array('admin')),
);
?>

<h1>Добавить книгу</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, "typeList" => $typeList)); ?>