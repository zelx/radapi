<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'radcheck-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'attribute',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'op',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span5','maxlength'=>253)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
