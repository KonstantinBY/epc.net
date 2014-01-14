<?php
    echo "<div class = 'news_preview'>";
echo "<h3><a href = '".Yii::app()->request->baseURL."/news/page/".$data->id."'>".$data->name."</a></h3>";
        echo '<h5>'.date("Y.m.d H:i" ,$data->create).' - '.$data->user.'</h5>';
        echo CHtml::encode($data->text);
    echo "</div>";
?>