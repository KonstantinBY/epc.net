<?php
/* @var $this PrintController */
/* @var $model Dictionary */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dictionary-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_eng'); ?>
		<?php echo $form->textField($model,'id_eng'); ?>
		<?php echo $form->error($model,'id_eng'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_ru'); ?>
		<?php echo $form->textField($model,'id_ru'); ?>
		<?php echo $form->error($model,'id_ru'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usage'); ?>
		<?php echo $form->textField($model,'usage'); ?>
		<?php echo $form->error($model,'usage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'usage_general'); ?>
		<?php echo $form->textField($model,'usage_general'); ?>
		<?php echo $form->error($model,'usage_general'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'part_search_id'); ?>
		<?php echo $form->textField($model,'part_search_id'); ?>
		<?php echo $form->error($model,'part_search_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->