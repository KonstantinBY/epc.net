<?php
/* @var $this FaqController */
?>
<h1>FAQ</h1>

<?php
    foreach ($model as $item) {
        echo "<div class = 'news_preview'>";
            echo $item->text;
        echo "</div>";
    }
?>