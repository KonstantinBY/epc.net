<?php

Yii::app()->clientScript->registerScript('search', "
$('.preview-button').click(function(){
	$('.preview-form').toggle();
	return false;
});
$('.preview-form form').submit(function(){
	$('#dictionary-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
$this->breadcrumbs=array(
	'Dictionaries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Dictionary', 'url'=>array('index')),
	array('label'=>'Manage Dictionary', 'url'=>array('admin')),
);
?>

<h1>Create Dictionary</h1>

<?php $this->renderPartial('_form', array('model'=>$model,
                                        'str_preview' => $str_preview,
                                        'str_save' => $str_save,
                                        'eng_str' => $eng_str,
                                        'ru_str' => $ru_str,
                                        'save_ready' => $save_ready,
                            ));
?>

