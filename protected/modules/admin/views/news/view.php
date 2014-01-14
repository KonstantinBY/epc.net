<?php
/* @var $this NewsController */
/* @var $model News */

$this->menu=array(
	array('label'=>'List News', 'url'=>array('index')),
	array('label'=>'Create News', 'url'=>array('create')),
	array('label'=>'Update News', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete News', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),

);
?>

<h1><?php echo $model->name; ?></h1>

<?php
echo "<div class = 'news'>";
echo "<div id = 'news_header'>";
echo '<h2>'.$model->name.'</h2>';
echo '<h5>'.date("Y.m.d H:i" ,$model->create).' - '.$model->user.'</h5>';
echo '</div>';
echo $model->text;
echo "</div>";
?>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		//'text',
		'create',
		'user',
	),
)); ?>
