<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
    'Login',
);
?>

<a href = "#"+javascript:x = 3; (x<5)? 'Спецификатор псевдопротокола': 'false' > asdasd</a>;
<h1>Новости</h1>
<hr>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_page',
)); ?>
