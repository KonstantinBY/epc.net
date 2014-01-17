<?php
/* @var $this PrintController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Dictionaries',
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.session.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('change_class_print', "
    jQuery(function($){

        if($.session.get('count_words') == '10'){

            $('.print_word').addClass('print_word_big');
            $('.print_min').html('Little Cards');
            console.log('load 10');
        }

    });
    $('.print_min').click(function(){
        $('.print_word').toggleClass('print_word_big');

             if($.session.get('count_words') == '10'){
                $('.print_min').html('Little Cards');
                $.session.set('count_words', '21');
                console.log('24');
             } else {
                $('.print_min').html('Big Cards');
                $.session.set('count_words', '10');
                console.log('10');
             }
        location.reload();
    });
");

Yii::app()->clientScript->registerScript('update', "

$(document).ready(function(){
        $('#button_update').click(function(){
                location.reload();
            })
        });
");

echo "<div id = 'print_tools'>";
    echo "<div class = 'print_min button'>Big Cards</div>";
    echo "<div id = 'button_update' class = 'button'>Update</div>";
    echo "<div class = 'button'> 3 x 8 </div>";

echo "</div>";
if(!empty($model)){
    echo "<div id = 'print_list'>";
        echo "<div class = 'print_center'>";
            //sort($model);
            //print_r($model);

            $count_words = 10;
            foreach($_COOKIE as $k => $cook){
                if(substr_count($k, 'count_words')){
                    $count_words = $cook;

                }
            }

            foreach($model as $item){
                $model_sort[] = $item->word_eng->word;
                $ru[] = $dictionary->findAllByAttributes(array('id_eng' => $item->word_eng->id));
            }

            //sort($model_sort);
            //print_r($eng_num);


            while(count($model_sort) % $count_words != 0){
                $model_sort[] = ' - ';
            }

            $i = 0;
            foreach($model_sort as $item){
                if(($i / $count_words) % 2 == 0){
                    if($item != ''){
                        echo "<div class = 'print_word eng'>";
                            echo '<div>' . $item . '</div>';
                            //echo $i / $count_words % 2;
                        echo "</div>";
                        }
                }else{
                    for($j = ($i - $count_words); $j < $i; $j++){
                        echo "<div class = 'print_word ru'>";
                            //echo "i = " . $i ." j = " . $j . " ($i / $count_words) % 2 = " . $i / $count_words % 2;
                            echo "<div>";
                                foreach($ru[$j] as $ru_w){
                                    echo "<div>" . $ru_w->word_ru->word . "</div>";
                                }
                            echo    "</div>";
                        echo "</div>";

                    }
                    $i += $count_words;
                }
                $i++;
            }
        echo "</div>";
    echo "</div>";
}