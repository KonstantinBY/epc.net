<?php
/* @var $this DictionaryController */
/* @var $model Dictionary */
/* @var $form CActiveForm */
?>

<div class="form import">


    <div id = "dictionary_form" class = 'page_menu form'>
        <?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>
        <?php echo CHtml::hiddenField('dictionary','import'); ?>
        <div class="row word">
            <?php echo CHtml::label('Import text', 'import_text');?>
            <?php echo CHtml::textArea('import_text', $str_save, array('class' => 'word_area'));?>
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

    </div>


</div><!-- form -->