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
            $('.print_min').html('3 x 7');
        }else{
        }

    });
    $('.print_min').click(function(){
        $('.print_word').toggleClass('print_word_big');

             if($.session.get('count_words') == '10'){
                $('.print_min').html('3 x 7');
                $.session.set('count_words', '21');
                console.log('24');
             } else {
                $('.print_min').html('2 x 5');
                $.session.set('count_words', '10');
                console.log('10');
             }
        location.reload();
    });
    $('.clean').click(function(){
        $.session.set('my_Words', '');
        location.reload();
    })
");

$count_words = 21;
foreach($_COOKIE as $k => $cook){
    if(substr_count($k, 'count_words')){
        $count_words = $cook;

    }
}

echo "<div id = 'print_tools'>";
    echo "<h4><span> (" . count($model) . ") слов</span></h2>";
    echo "<div class = 'print_min button'>2 x 5</div>";
    echo "<div class = 'clean button'>Очистить</div>";
    /*echo "<div id = 'button_update' class = 'button'>Update</div>";
    echo "<div class = 'button'> 3 x 8 </div>";*/
echo "</div>";

if(!empty($model)){
    echo "<div id = 'print_list'>";
        echo "<div class = 'print_center'>";
            //sort($model);

            foreach($model as $item){
                $model_sort[] = $item->word_eng->word;
                $ru[] = $dictionary->findAllByAttributes(array('id_eng' => $item->word_eng->id));
            }

            //sort($model_sort);
            //print_r($eng_num);

            $count_model = count($model_sort);
            while(count($model_sort) % $count_words != 0){
                $model_sort[] = '   ';
                //$ru[] = ' - ';
            }

            $page = 1;
            $end = false;
            $x = 0;
            //print_r($model_sort);
            while(!$end){
                echo "<div class = 'print_page'>";
                    for($i = ($page * $count_words) - $count_words; $i < ($page * $count_words ); $i++){
                        if(isset($model_sort[$i])){
                            echo "<div class = 'print_word eng'>";
                                echo '<div>' . $model_sort[$i] . '</div>';
                            echo "</div>";
                            //$x++;
                        }else{
                            echo "<div class = 'print_word eng'>";
                                echo '<div> Model Empty </div>';
                            echo "</div>";
                            //$x++;
                        }
                    }
                echo "</div>";

                echo "<div class = 'page_separator'></div>";

                echo "<div class = 'print_page'>";
                    for($j = ($page * $count_words) - $count_words; $j < ($page * $count_words) ; $j++){
                        if(!isset($ru[$j])){
                            continue;
                        }
                        if(isset($ru[$j])){
                            echo "<div class = 'print_word ru'>";
                                echo "<div>";
                                    foreach($ru[$j] as $ru_w){
                                        echo "<div>" . $ru_w->word_ru->word . ";</div>";
                                    }
                                echo "</div>";
                            echo "</div>";
                            $x++;
                        }else{
                            echo "<div class = 'print_word eng'>";
                                echo '<div> Model Empty </div>';
                            echo "</div>";
                            $x++;
                        }
                    }
                echo "</div>";
                echo "<div class = 'page_separator'></div>";

                if(($x) >= $count_model){
                    $end = true;
                }
                $page++;
                //echo $x . " / " . $count_model;
            }
        echo "</div>";
    echo "</div>";
}