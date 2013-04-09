<?php
$this->breadcrumbs=array(
	'Radchecks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Radcheck','url'=>array('index')),
	array('label'=>'Manage Radcheck','url'=>array('admin')),
);
?>

<h1>Create Radcheck</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>