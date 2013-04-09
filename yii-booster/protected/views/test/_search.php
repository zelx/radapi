<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'UserID',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'FirstName',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'LastName',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'Address',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'Phone',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'Email',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'Mac',array('class'=>'span5','maxlength'=>17)); ?>

	<?php echo $form->textFieldRow($model,'RegisDate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'Status',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
