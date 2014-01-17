<?php
/* @var $this DictionaryOffenController */
/* @var $data Dictionary */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_eng')); ?>:</b>
	<?php echo CHtml::encode($data->id_eng); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_ru')); ?>:</b>
	<?php echo CHtml::encode($data->id_ru); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usage')); ?>:</b>
	<?php echo CHtml::encode($data->usage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('usage_general')); ?>:</b>
	<?php echo CHtml::encode($data->usage_general); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('part_search_id')); ?>:</b>
	<?php echo CHtml::encode($data->part_search_id); ?>
	<br />


</div>