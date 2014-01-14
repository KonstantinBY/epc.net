<?php
/* @var $this NewsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'News'=>array('index'),
    $model->name=>array('view','id'=>$model->id),
    'Update',
);

?>
<h1>Новости</h1>
<hr>

<?php
    echo "<div class = 'news'>";
        echo "<div id = 'news_header'>";
            echo '<h2>'.$model->name.'</h2>';
            echo '<h5>'.date("Y.m.d H:i" ,$model->create).' - '.$model->user.'</h5>';
        echo '</div>';
        echo $model->text;
    echo "</div>";
?>