<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'Create News', 'url'=>array('create')),
);
?>

<h1>Manage News</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'text' => array(
            'name' => 'text',
            'value' => 'mb_substr(strip_tags(trim($data->text)), 0,100,"utf-8")',
            'headerHtmlOptions' => array('width' => '300px'),
        ),
        'create'=>array(
            'name'=>'create',
            'value'=>'date("Y.m.d H:i",$data->create)'
        ),
		'user',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
