<?php
$this->breadcrumbs=array(
	'Radchecks',
);

$this->menu=array(
	array('label'=>'Create Radcheck','url'=>array('create')),
	array('label'=>'Manage Radcheck','url'=>array('admin')),
);
?>

<h1>Radchecks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
