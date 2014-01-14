<?php
/* @var $this NewsController */
/* @var $model News */


$this->menu=array(
	array('label'=>'List News', 'url'=>array('index')),
);
?>

<h1>Create News</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>