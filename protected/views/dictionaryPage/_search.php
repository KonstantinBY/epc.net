<?php
/* @var $this DictionaryPageController */
/* @var $model Dictionary */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_eng'); ?>
		<?php echo $form->textField($model,'id_eng'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_ru'); ?>
		<?php echo $form->textField($model,'id_ru'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'usage'); ?>
		<?php echo $form->textField($model,'usage'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'usage_general'); ?>
		<?php echo $form->textField($model,'usage_general'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'part_search_id'); ?>
		<?php echo $form->textField($model,'part_search_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->