<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 1/29/14
 * Time: 4:12 PM
 */

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
    array('label'=>'Add Words', 'url'=>array('create')),
);
?>

<h1>Create Dictionary</h1>

<?php
    $this->renderPartial('_import', array(
        'model'=>$model,
        'save_ready' => $save_ready,
        'str_preview'=>$str_preview,
        'str_save' => $str_save,

    ));
?>

