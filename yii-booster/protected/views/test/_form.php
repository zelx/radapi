<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'FirstName',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->textFieldRow($model,'LastName',array('class'=>'span5','maxlength'=>200)); ?>

	<?php echo $form->labelEx($model,'Address'); ?>
	<?php echo $form->textArea($model,'Address',array('class'=>'span5','maxlength'=>200,'rows'=>6, 'cols'=>50)); ?>

	<?php echo $form->textFieldRow($model,'Phone',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'Email',array('class'=>'span5','maxlength'=>200)); ?>
	
	<?php echo $form->textFieldRow($model,'Mac',array('class'=>'span5','readOnly'=>'readOnly','maxlength'=>17,'value'=>$_GET['mac'])); ?>

	<?php $form->textFieldRow($model,'RegisDate',array('class'=>'span5')); ?>

	<?php $form->textFieldRow($model,'Status',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
