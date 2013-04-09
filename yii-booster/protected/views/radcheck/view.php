<?php
$this->breadcrumbs=array(
	'Radchecks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Radcheck','url'=>array('index')),
	array('label'=>'Create Radcheck','url'=>array('create')),
	array('label'=>'Update Radcheck','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Radcheck','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Radcheck','url'=>array('admin')),
);
?>

<h1>View Radcheck #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'attribute',
		'op',
		'value',
	),
)); ?>
