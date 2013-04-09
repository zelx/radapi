<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->UserID=>array('view','id'=>$model->UserID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Users','url'=>array('index')),
	array('label'=>'Create Users','url'=>array('create')),
	array('label'=>'View Users','url'=>array('view','id'=>$model->UserID)),
	array('label'=>'Manage Users','url'=>array('admin')),
);
?>

<h1>Update Users <?php echo $model->UserID; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>