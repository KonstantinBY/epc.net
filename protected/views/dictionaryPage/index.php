<?php
/* @var $this DictionaryPageController */
/* @var $model Dictionary */

$this->breadcrumbs = array(
    'Dictionaries' => array('index'),
    'Manage',
);

?>

<script type = "text/javascript">

</script>

<?php
//$_SESSION['myVar'] = array();
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.session.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('change_class_words', "

    mw = [];
    jQuery(function($){
        if(typeof $.session.get('my_Words') !== 'undefined') {
            mw = JSON.parse('[' + $.session.get('my_Words') + ']');
            console.log(mw);
        }
    });

    $('.main_word').click(function(){
        $(this).toggleClass('red');
        word_id = $(this).attr('id');
        console.log(mw);

        var removed = 0;
        $.each(mw, function(k, v){
                if (v == word_id) {
                    mw.splice(k,1);
                    removed = 1;
                }
            }
        );
        if (!removed) {
            mw.push(word_id);
        }
        console.log(mw);
        $.session.set('my_Words', mw);
    });
");

//print_r($_COOKIE);

$cur_words = array();
if($_COOKIE){
    foreach($_COOKIE as $k => $cook){
        if(substr_count($k, 'my_Words')){
            $cur_words = explode(',', $cook);
        }
    }
}

//sort($cur_words);
//print_r($cur_words);

?>
<div class = "left_title">
    <h2>Словарь <span>выберите слова для печати и перейдите в меню “Print”</span></h2>
    <h4>Отсартированны по алфавиту</h4>
</div>

<div id = 'print_tools_horizont'>
    <div class = 'print_min button'>Сорт. по частоте</div>
    <div class = 'button'> 3 x 8 </div>
    <div class = 'button'> 3 x 8 </div>
</div>

<div id = "dictionary_page">
    <?php
        //echo $model[2][1][1][id];
        //print_r($model);


    echo "<div id = 'dictionary_border'></div>";
        $red_word = 0;
        sort($model);

        //print_r($model[0][1][0]);
        foreach($model as $items){
            $red_word = 0;
            foreach($cur_words as $word_id){
                if($word_id == $items[1][0]['id']){
                    $red_word = 1;
                }
            }
            $cur_class = 'main_word';
            if(($red_word) && $cur_words != 0){
                $cur_class = "class = 'main_word red'";
            }else{ $cur_class = "class = 'main_word'";}
            echo "<div id = ". $items[1][0]['id'] . "  " . $cur_class . ">";
                echo $items[0];
                //print_r($items);
            echo "</div>";
                echo "<div class = 'second_word'>";
            $noun = array();
            $adj = array();
            $verb = array();
            $pronoun = array();
            $numeral= array();
            $adverb = array();
            $prepos = array();
            $conjun = array();
            $particle = array();
            $interject = array();
            $prich = array();
            $deeprich = array();
            $article = array();
            $unknow= array();
                foreach($items[1] as $item){
                    $word = $model_ru->model()->findByPk($item['id_ru'])->word . "<br>";

                    switch ($item['part_search_id']){
                        case 1: $noun[] = $word;
                            break;
                        case 2: $adj[] = $word;
                            break;
                        case 3: $verb[] = $word;;
                            break;
                        case 4: $pronoun[] = $word;
                            break;
                        case 5: $numeral[] = $word;
                            break;
                        case 6: $adverb[] = $word;
                            break;
                        case 7: $prepos[] = $word;
                            break;
                        case 8: $conjun[] = $word;;
                            break;
                        case 9: $particle[] = $word;
                            break;
                        case 10: $interject[] = $word;
                            break;
                        case 11: $prich[] = $word;
                            break;
                        case 12: $deeprich[] = $word;
                            break;
                        case 13: $article[] = $word;;
                            break;
                        case 14: $unknow[] = $word;
                            break;
                        default: $unknow[] = $word;
                    }

                }
            outPart($noun, "Существительное");
            outPart($adj, "Прилогательное");
            outPart($verb, "Глагол");
            outPart($pronoun, "Местоимение");
            outPart($numeral, "Числительное");
            outPart($adverb, "Наречие");
            outPart($prepos, "Предлог");
            outPart($conjun, "Союз");
            outPart($particle, "Частица");
            outPart($interject, "Междометие");
            outPart($prich, "Причастие");
            outPart($deeprich, "Деепричастие");
            outPart($article, "Артикль");
            outPart($unknow, "Не определено");

            echo '</div>';

        }

    ?>

</div>

<?php


function outPart($arr, $part){
    if($arr){
        echo "<h5>$part</h5>";
        foreach($arr as $a){
            echo $a;
        }
    }
}
?>