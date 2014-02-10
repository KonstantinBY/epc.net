<?php
/* @var $this DictionaryPageController */
/* @var $model Dictionary */

$this->breadcrumbs = array(
    'Dictionaries' => array('index'),
    'Manage',
);

?>

<div id = "loader" class = ''></div>

<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.session.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('change_class_words', "

    mw = [];
    jQuery(function($){
        if(typeof $.session.get('my_Words') !== 'undefined') {
            mw = JSON.parse('[' + $.session.get('my_Words') + ']');
            console.log(mw);
        }

        $('#alphabet a').live('click', function () {
            var self = $(this);
            var href = self.attr('href');
            $.ajax({
                url: href,
                dataType: 'html',
                beforeSend: function() {
                    $('#loader').addClass('loading');
                }
            })
            .done(function(response) {
                var newPage = $(response);
                $('#content').html(newPage.find('#content').html());
                $('#loader').removeClass('loading');

            })
            .fail(function() {
                alert( 'error' );
            });

            return false;
        })

        var self = $('#search_word_area');
        var letter = self.val();
        $('.main_word').each(function(k, v){
         var element = $(v);
            var result = element.text().toLowerCase().indexOf(letter.toLowerCase(),0);  // поиск шаблона в юрл
            if (result != 0) {
                element.hide();
            } else {
                element.show();
            }
        });

        ////////
        $('#search_word_area').live('keyup', function () {

            var self = this;
            var letter = $(this).val();
            $('.main_word').each(function(k, v){
             var element = $(v);
                var result = element.text().toLowerCase().indexOf(letter.toLowerCase(),0);  // поиск шаблона в юрл
                if (result != 0) {
                    element.hide();
                } else {
                    element.show();
                }
            });
        });
    });

    $('.main_word').live ('click', function(){
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
    $('.clean').live('click', function(){
        $('#loader').addClass('loading');
        $.session.set('my_Words', '');
        location.reload();
    })

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
    <h4>
        Отсартированны по алфавиту
        <?php echo "<span> (" . count($model) . ") слов</span>"?>
    </h4>
    </h4>
</div>

<div id = 'alphabet'>
    <?php
        if($idLetter == 'all'){
            echo CHtml::link('All', array('dictionaryPage/index', 'letter' => 'all'), array('class' => 'active'));
        }else{
            echo CHtml::link('All', array('dictionaryPage/index', 'letter' => 'all'));
        }
    ?>
    <?php
        $letters = range('a', 'z');
        foreach ($letters as $letter) {
            $htmlOptions = array();
            if ($idLetter == $letter) {
                $htmlOptions['class'] = 'active';
            }
            echo CHtml::link(strtoupper($letter), array('dictionaryPage/index', 'letter' => $letter), $htmlOptions);
        }
    ?>
</div>

<div id = 'print_tools_horizont'>
    <input type = 'text' id = "search_word_area" title = 'Введите начальные буквы слова' value="">
    <div class = 'clean button'>Очистить</div>
</div>

<div id = "dictionary_page">
    <?php

    echo "<div id = 'dictionary_border'></div>";
        $red_word = 0;
        sort($model);

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
            echo "</div>";

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
            $reduction = array();
                foreach($items[1] as $item){
                    $word = $model_ru->model()->findByPk($item['id_ru'])->word;

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
                        case 15: $reduction[] = $word;
                            break;
                        default: $unknow[] = $word;
                    }
                }
            echo "<div class = 'second_word'>";
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
                outPart($reduction, "Сокращение");
            echo '</div>';
        }
    ?>
</div>

<?php
function outPart($arr, $part){
    if($arr){
        echo "<h5>$part</h5>";
        foreach($arr as $a){
            echo "<div>" . $a . "; </div>";
        }
    }
}
?>