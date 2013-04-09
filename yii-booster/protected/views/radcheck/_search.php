<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'attribute',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'op',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span5','maxlength'=>253)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
