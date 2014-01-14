<?php
    $this->breadcrumbs=array(
        'Site'=>array('index'),
        'Create',
    );
    echo "<div class = 'news_preview'>";
        echo "<h3><a href = '".Yii::app()->request->baseURL."/news/page/".$data->id."'>".$data->name."</a></h3>";
        echo '<h5>'.date("Y.m.d H:i" ,$data->create).' - '.$data->user.'</h5>';
        echo mb_substr(strip_tags($data->text),0,400,"utf-8"); //180
        echo CHtml::link('<br>Read more...',array("/news/page/",'id'=>$data->id));
    echo "</div>";
?>