<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'FirstName'); ?>
		<?php echo $form->textField($model,'FirstName',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'FirstName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LastName'); ?>
		<?php echo $form->textField($model,'LastName',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'LastName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Address'); ?>
		<?php echo $form->textField($model,'Address',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'Address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Phone'); ?>
		<?php echo $form->textField($model,'Phone',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'Phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Email'); ?>
		<?php echo $form->textField($model,'Email',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'Email'); ?>
	</div>

	<div class="row">
		<?php $model->Mac=$mac;?>
		<?php echo $form->labelEx($model,'Mac'); ?>
		<?php echo $form->textField($model,'Mac',array('size'=>17,'maxlength'=>17)); ?>
		<?php echo $form->error($model,'Mac'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'RegisDate'); ?>
		<?php echo $form->textField($model,'RegisDate'); ?>
		<?php echo $form->error($model,'RegisDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Status'); ?>
		<?php echo $form->textField($model,'Status'); ?>
		<?php echo $form->error($model,'Status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->