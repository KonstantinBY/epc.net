<?php
    echo "<div class = 'news'>";
        echo '<h3>'.$model->name.'</h3>';
        echo '<h5>'.date("Y.m.d H:i" ,$model->create).' - '.$model->user.'</h5>';
        echo CHtml::encode($model->text);
    echo "</div>";
?>