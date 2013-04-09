<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Users','url'=>array('index')),
	array('label'=>'Create Users','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('users-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'fixedHeader' => true,
	
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'UserID',
		'FirstName',
		'LastName',
		'Address',
		'Phone',
		'Email',
		/*
		'Mac',
		'RegisDate',
		'Status',
		*/
		array(
			//'header'=>'Edit',
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>

<?php

$this->widget('bootstrap.widgets.TbJsonGridView', array(
	'dataProvider' => $model->search(),
	//'filter' => $model,
	'type' => 'striped bordered condensed',
	'summaryText' => false,
	'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
	'cacheTTLType' => 's', // type can be of seconds, minutes or hours
	'columns' => array(
		'UserID',
		'FirstName',
		'LastName',
		'Address',
		'Phone',
		'Email',

		array(
			
			'class' => 'bootstrap.widgets.TbJsonButtonColumn',
			'template' => '{view}{update} {delete}',
		),
	),
	)); ?>
