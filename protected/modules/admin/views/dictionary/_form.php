<?php
/* @var $this DictionaryController */
/* @var $model Dictionary */
/* @var $form CActiveForm */
?>

<div class="form">


    <div id = "dictionary_form" class = 'page_menu form'>
        <?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>
        <?php echo CHtml::hiddenField('dictionary','create'); ?>
        <div class="row word">
            <?php echo CHtml::label('English word', 'eng_word');?>
            <?php echo CHtml::textArea('eng_word', $eng_str, array('class' => 'word_area'));?>
        </div>
        <div class="row word">
            <?php echo CHtml::label('Russian word', 'ru_word');?>
            <?php echo CHtml::textArea('ru_word', $ru_str,array('class' => 'word_area'));?>
        </div>
        <?php echo CHtml::submitButton('Preview',array('name'=>'preview', 'class' => 'page_manager_button')); ?>

        <?php
            if ($save_ready) {
                echo CHtml::submitButton('Save',array('name'=>'save', 'class' => 'page_manager_button'));
            }
        ?>

        <?php echo $this->renderPartial('_preview',array(
                'model'=>$model,
                'str_preview'=>$str_preview,
                'str_save' => $str_save,
                ));
        ?>
        <?php echo CHtml::endForm(); ?>
        <div id = "dictionary_faq">
            <div><?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/admin_tools/faq_01.png', 'обозначение разметка слов' )?></div>
            <div>
                <h4>Части речи</h4>
                <div>
                    <ul title="Части речи">
                        <li>1	существительное</li>
                        <li>2	прилагательное</li>
                        <li>3	глагол</li>
                        <li>4	местоимение</li>
                        <li>5	числительное</li>
                        <li>6	наречие</li>
                        <li>7	предлог</li>
                    </ul>
                </div>
                <div>
                    <ul>
                        <li>8	союз</li>
                        <li>9	частица</li>
                        <li>10	междометие</li>
                        <li>11	причастие</li>
                        <li>12	деепричастие</li>
                        <li>13	артикль</li>
                        <li>14 не определено</li>
                     </ul>
                </div>
            </div>
            <div>
                <h4>Частота использования</h4>
                <ul title="Частота использования">
                    <li>1	редкое употребление</li>
                    <li>2	не обычное употребление</li>
                    <li>3	обычное употребление</li>
                </ul>
            </div>
        </div>
    </div>

<?php /*$form=$this->beginWidget('CActiveForm', array(
	'id'=>'dictionary-form',
	// Please note: When you enable ajax validation, make sure the 5corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row word">
		<?php echo $form->labelEx($model,'id_eng'); ?>
		<?php echo $form->textArea($model,'id_eng',array('style' => 'width: 150px; height: 200px')); ?>
		<?php echo $form->error($model,'id_eng'); ?>
	</div>

	<div class="row word">
		<?php echo $form->labelEx($model,'id_ru'); ?>
		<?php echo $form->textArea($model,'id_ru',array('style' => 'width: 150px; height: 200px')); ?>
		<?php echo $form->error($model,'id_ru'); ?>
	</div>



    <?php echo CHtml::link('Advanced Search','#',array("class"=>"preview-button")); ?>

    <div class= 'preview-form'  style='display:none'>
        <?php $this->renderPartial('_preview',array('model'=>$model)); ?>
    </div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Preview'); ?>
	</div>

<?php $this->endWidget(); */?>

</div><!-- form -->