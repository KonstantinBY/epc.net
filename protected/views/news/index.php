<?php
/* @var $this NewsController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Новости</h1>
<hr>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_page',
)); ?>
