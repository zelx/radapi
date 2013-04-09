<?php
$this->breadcrumbs=array(
	'Radchecks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Radcheck','url'=>array('index')),
	array('label'=>'Create Radcheck','url'=>array('create')),
	array('label'=>'View Radcheck','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Radcheck','url'=>array('admin')),
);
?>

<h1>Update Radcheck <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>