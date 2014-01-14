<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
    'Login',
);

?>

<h1>Новости</h1>
<hr>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_page',
)); ?>
