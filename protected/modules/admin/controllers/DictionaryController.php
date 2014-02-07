<?php

class DictionaryController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout='//layouts/column2';

    /**
     * @return array action filters	 */


    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','view'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'roles'=>array('manager'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin', 'delete', 'create', 'update', 'import'),
                'roles'=>array('admin'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /*@items - two-dimensional array $items[][word,word,...]
    * @key - save keys
    * return array[word]
    */
    public function wordToArray($items){
        $return_arr = array();
        $temp_arr = array();

        //print_r($items);
        foreach($items as $key => $item){
            $temp_arr = array();
            if((!empty($item[1]))){

                //$temp_arr = preg_split("/(?=\()[;,](?!=\))/", $item, 4, PREG_SPLIT_NO_EMPTY);
                //$temp_arr = preg_split("/[;,]/", $item, -1, PREG_SPLIT_NO_EMPTY);

                //Берем все скобки
                /*
                preg_match_all('/\(.*?\)/i', $item, $matches);
                $str1 = preg_replace('/\(.*?\)/i', '',$item);
                $items_comma = preg_split("/[;,()]/", $str1, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($matches as $m1) {
                    foreach($m1 as $m2){
                        $temp_arr[] = $m2;
                    }
                }
                foreach($items_comma as $ic){
                    $temp_arr[] = $ic;
                }
                */

                $brackets = false;
                $last_char = 0;
                $little_br = 0;
                $br_start = 0;
                //Перебор строки по символам
                for($i = 0; $i < strlen($item); $i++) {
                    while($item[$last_char] == ' '){
                        $last_char++;
                        //echo $last_char . '- last_char';
                    }
                    if($item[$i] == "("){
                        if((!$brackets) && ($i > 1) && $item[$i -1] == ' '){
                            if(trim(substr($item, $last_char, $i - $last_char - 1)) != 0){
                                $temp_arr[] = substr($item, $last_char, $i -$last_char - 1);
                            }
                            $last_char = $i+1;
                        }
                        $br_start = $i;
                        $brackets = true;
                    }
                    if($item[$i] == ")"){

                        $last_char = $little_br;
                        $brackets = false;
                    }
                    if((!$brackets) && (($item[$i] == ';') or ($item[$i] == ',') )){
                        if(trim(substr($item, $last_char, $i - $last_char)) != false){
                            $temp_arr[] = substr($item, $last_char, $i - $last_char);
                        }
                        $last_char = $i+1;
                        $little_br = $last_char;
                    }
                    if(
                    (($i+1 >= strlen($item)))){

                        if(strlen(trim(substr($item, $last_char, $i - $last_char+1))) > 0){
                            $temp_arr[] = substr($item, $last_char, $i - $last_char+1);
                        }
                    }
                }


                if(!empty($temp_arr)){
                    $return_arr[] = $temp_arr;
                }

            }
        }
        //var_dump($return_arr);
        return $return_arr;
    }


    public function actionImport()
    {
        $model = '';
        $save_ready = false;
        $str_preview = '';
        $str_save ='';
        $result_part_search = array();

        if(isset($_POST['dictionary']) == 'import')
        {
            /*Принимает POST с текстом, заносит в массив
             * разделив через \r
             */
            $str_save = $_POST["import_text"];
            $import_words = preg_split("/[\r]/",$_POST["import_text"]);
            $item_namber = 0;

            /*Удаляет пустые строки
             * */
            $import_words = array_filter($import_words, function($item) {
                return ($item != "\n");
            });

            /*Обработка массива всех строк разделенных по \r
             * varable for save :
             * $eng_word, $transcription, $result_part_search[], $usage
             *
             * */
            foreach($import_words as $import_item){
                if((count($import_words) == 1) && ($import_words[0]=='')) break;

                $import_item = trim($import_item);
                $eng_word = mb_substr($import_item, 0, mb_stripos($import_item, "[", 0, 'utf-8') - 1, 'utf-8');
                $transcription = mb_substr($import_item,
                    mb_stripos($import_item, "[", 0, 'utf-8'),
                    mb_stripos($import_item, "]", 0, 'utf-8') - mb_stripos($import_item, "[", 0, 'utf-8') + 1,
                    'utf-8'
                );

                $usage = substr($import_item,
                    strrpos($import_item, ' ') + 1,
                    (mb_strlen($import_item) - strrpos($import_item, ' '))
                );

                $import_item = substr($import_item,
                    mb_stripos($import_item, "]", 0, 'utf-8') + 1,
                    strrpos($import_item, ' ') - mb_stripos($import_item, "]", 0, 'utf-8')
                );
                //$import_item = tirm($import_item);



                $part_search_pos = array(
                    "adjective" => mb_stripos($import_item, 'a-', 0, 'utf-8'),
                    "adverb" => mb_stripos($import_item, "adv-", 0, 'utf-8'),
                    "conjunction" => mb_stripos($import_item, "cj-", 0, 'utf-8'),
                    "interjection" => mb_stripos($import_item, "int-", 0, 'utf-8'),
                    "noun" => mb_stripos($import_item, "n-", 0, 'utf-8'),
                    "particle" => mb_stripos($import_item, "part-", 0, 'utf-8'),
                    "preposition" => mb_stripos($import_item, "prep-", 0, 'utf-8'),
                    "pronoun" => mb_stripos($import_item, "pron-", 0, 'utf-8'),
                    "verb" => mb_stripos($import_item, "v-", 0, 'utf-8'),
                    "numeral" => mb_stripos($import_item, "num-", 0, 'utf-8'),
                );

                //echo $import_item . "<br>";

                $trans_str = '';
                $psp = array();

                foreach($part_search_pos as $key => $item){
                    if($item === 0){
                        $import_item = substr_replace($import_item, ' ', 0, 0);
                        $item++;
                    }

                    if(($item)
                        && (mb_substr($import_item, $item - 1, 1, "utf-8") == " ")

                    ){
                        $psp[$key] = $item;
                    }
                }

                if(count($psp) == 0){
                    $psp['unknown'] = 1;
                    $import_item = substr_replace($import_item, ' -', 0, 0);
                }
                natsort($psp);
                $arr_part_search = array();
                $psp_keys = array_keys($psp);
                for($i = 0; $i < count($psp); $i++){
                    if(!empty($psp_keys[$i + 1])){
                        $str = mb_substr($import_item, $psp[$psp_keys[$i]], $psp[$psp_keys[$i + 1]] - $psp[$psp_keys[$i]], "utf-8");
                        $str2 = mb_substr($str, mb_stripos($str, '-') + 1, mb_strlen($str) - mb_stripos($str, '-') + 1,'utf-8');
                        $arr_part_search[] = trim($str2);
                    }else{
                        $str = mb_substr($import_item, $psp[$psp_keys[$i]], mb_strlen($import_item, "utf-8") - $psp[$psp_keys[$i]], "utf-8");
                        $str2 = trim(mb_substr($str, mb_stripos($str, '-') + 1, mb_strlen($str) - mb_stripos($str, '-') + 1,'utf-8'));
                        $arr_part_search[] = trim($str2);
                    }
                    $result_part_search = self::wordToArray($arr_part_search);
                }
                $result_part_search = array_combine($psp_keys, $result_part_search);

                foreach ($result_part_search as $key => $ap_1) {
                    $trans_str .=  "\r $key {\r";

                    foreach($ap_1 as $ap_2){
                        //$ap_2 = trim($ap_2);
                        $trans_str .=  "-" . $ap_2 . "\r";
                    }
                    $trans_str .=  "}";
                }

                $str_preview .=
                    //$import_item .
                    "\r[" . $item_namber . "] - " .
                    $eng_word . "-".
                    $transcription . "-".
                    $trans_str . "\r".
                    $usage . "\r\r"
                ;

                if(isset($_POST['save'])){
                    //$str_preview = 'Saved';
                    $eng_id = false;
                    $ru_id = false;

                    foreach ($result_part_search as $key => $a_ps) {
                        foreach($a_ps as $ru_word){
                            $word_ru_save = new WordRu();
                            $word_eng_save = new WordEng();
                            $model_save = new Dictionary();

                            if($word_eng_save->model()->findByAttributes(array('word' => $eng_word))){
                                $eng_id = $word_eng_save->model()->findByAttributes(array('word' => $eng_word))->id;
                                $str_preview .= "\n - $eng_word есть - " . $eng_id;
                            }else{
                                $word_eng_save->word = $eng_word;
                                $word_eng_save->transcription = $transcription;
                                $word_eng_save->save();
                                $eng_id = false;
                                $str_preview .= "\n - $eng_word создано - " . $eng_word . "-" . $transcription;
                            }

                            if($word_ru_save->model()->findByAttributes(array('word' => $ru_word))){
                                $ru_id = $word_ru_save->model()->findByAttributes(array('word' => $ru_word))->id;
                                $str_preview .= " - $ru_word есть - " . $ru_id;
                            }else{
                                $word_ru_save->word = $ru_word;
                                $word_ru_save->save();
                                $ru_id = false;
                                $str_preview .= "- $ru_word создано - " . $ru_word;
                            }

                            //Если английское слово уже есть, то даем на него ссылку
                            //если нету, то даем ссылку на только что созданное
                            if($eng_id != false){
                                $model_save->id_eng = $eng_id;
                            }else{
                                $model->save->id_eng = $word_eng_save->id;
                            }

                            //Если русское слово уже есть, то даем на него ссылку
                            //если нету, то даем ссылку на только что созданное
                            if($ru_id != false){
                                $model_save->id_ru = $ru_id;
                            }else{
                                $model_save->id_ru = $word_ru_save->id;
                            }

                            $model_save->usage_general = $usage;
                            $model_save->usage = 3;

                            switch ($key){
                                case 'adjective': $model_save->part_search_id = 2;
                                    break;
                                case 'adverb': $model_save->part_search_id = 6;
                                    break;
                                case 'conjunction': $model_save->part_search_id = 8;
                                    break;
                                case 'interjection': $model_save->part_search_id = 10;
                                    break;
                                case 'noun': $model_save->part_search_id = 1;
                                    break;
                                case 'particle': $model_save->part_search_id = 9;
                                    break;
                                case 'preposition': $model_save->part_search_id = 7;
                                    break;
                                case 'pronoun': $model_save->part_search_id = 4;
                                    break;
                                case 'verb': $model_save->part_search_id = 3;
                                    break;
                                case 'numeral': $model_save->part_search_id = 5;
                                    break;
                                default: $model_save->part_search_id = 14;
                            }


                            if(($eng_id) && ($ru_id)){
                                $items = $model_save->findAllByAttributes(array('id_eng' => $eng_id));
                                $flag = false;
                                foreach($items as $item){
                                    if($item['id_ru'] == $ru_id){
                                        $flag = true;
                                    }
                                }
                                if(!$flag){
                                    if(!$model_save->save()){
                                        $str_preview = "Не правильно заполнены поля";
                                    }
                                }else{
                                    $str_preview .= " - Link didn't create" ;
                                }
                            }

                        }//foreach_inside
                        $str_preview .= "\n";
                    } //end foreach
                }//end save

                $item_namber++;

            }//end foreach

            $save_ready = true;
            /*
            * varable for save :
            * $eng_word, $transcription, $result_part_search[part_search][value], $usage
            */

        }//end import


        $this->render('import',array(
            'model'=>$model,
            'save_ready' => $save_ready,
            'str_preview'=>$str_preview,
            'str_save' => $str_save,

        ));
    }


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $partSearch = PartSearch::model()->findAll();
        $model = '';
        $str_preview = '';
        $str_save = '';
        $eng_word = '';
        $ru_word = '';
        $save_ready = '';

        if(isset($_POST['dictionary']) == 'create')
        {
            $eng_word = $_POST['eng_word'];
            $ru_word = $_POST['ru_word'];
            //$arr_eng = array();
            //$str_preview = '';

            //Улучшить проверку
            if(isset($_POST['preview'])){
                $items_eng = preg_split("/[\r]/", $eng_word);
                $arr_eng = self::wordToArray($items_eng);

                $items_ru = preg_split("/[\r]/", $ru_word);
                $arr_ru = self::wordToArray($items_ru);

                if(!empty($eng_word)&& !empty($ru_word)){
                    $str_preview = $eng_word . "\n--------------\n" . $ru_word;
                    $save_ready = true;
                } else {
                    $str_preview = "Не все поля заполнены";
                }
            }

            /*
             *
             *
             * */
            if(isset($_POST['save'])){

                $items_eng = preg_split("/[\r]/", $eng_word);
                $arr_eng = self::wordToArray($items_eng);

                $items_ru = preg_split("/[\r]/", $ru_word);
                $arr_ru = self::wordToArray($items_ru);

                //по количеству записей в массиве определяет основной язык (чем меньше слов тем главнее)
                // в дальнейшем из массива с основным языком выбирается только первое слово,
                //для которого создаются ссылки из второго массива
                if(count($arr_eng) <= count($arr_ru)){
                    $current_len = "eng";
                }
                else{
                    $current_len = "ru";
                }

                //Если текущий язык английский
                if($current_len == "eng"){
                    $str_preview = "Saved !!! \r" . $current_len ." -- ". count($arr_eng) ." -- ". count($arr_ru);;
                    $model_eng = new WordEng;
                    //Если в базе WordEng есть английское слово
                    //запоминаем его id, что бы дать на него ссылку
                    //если нету, создаем новое слово
                    if($model_eng->model()->findByAttributes(array('word' => $arr_eng[0][0]))){
                        $eng_id = $model_eng->model()->findByAttributes(array('word' => $arr_eng[0][0]))->id;
                        $str_preview .= "\nесть eng- " . $model_eng->model()->findByPk($eng_id)->word . "(" . $eng_id . ")";
                    }else{
                        $model_eng->word = $arr_eng[0][0];
                        $model_eng->save(false);
                        $eng_id = false;
                    }
                    //Заносим русские слова в базу
                    foreach($arr_ru as $arr_ru_item){
                        $model_ru = new WordRu;
                        $model= new Dictionary;


                        //Если в базе WordRu есть русское слово
                        //запоминаем его id, что бы дать на него ссылку
                        //если нету, создаем новое слово
                        if($model_ru->model()->findByAttributes(array('word' => trim($arr_ru_item[0])))){
                            $ru_id = $model_ru->model()->findByAttributes(array('word' => trim($arr_ru_item[0])))->id;
                            $str_preview .= "\nесть ru- " . $model_ru->model()->findByPk($ru_id)->word . "(" . $ru_id . ")";
                        }else{
                            $model_ru->word = trim($arr_ru_item[0]);
                            $model_ru->save(false);
                            $ru_id = false;
                        }

                        //Если английское слово уже есть, то даем на него ссылку
                        //если нету, то даем ссылку на только что созданное
                        if($eng_id != false){
                            $model->id_eng = $eng_id;
                        }else{
                            $model->id_eng = $model_eng->id;
                        }

                        //Если русское слово уже есть, то даем на него ссылку
                        //если нету, то даем ссылку на только что созданное
                        if($ru_id != false){
                            $model->id_ru = $ru_id;
                        }else{
                            $model->id_ru = $model_ru->id;
                        }

                        //Проверка валидности Типы речи номируются  1-14
                        //14 неопределенная часть речи
                        if(($arr_ru_item[1] < 1) || ($arr_ru_item[1] > count($partSearch))){
                            $model->part_search_id = 14;
                        }else{
                            $model->part_search_id = trim($arr_ru_item[1]);
                        }

                        //Проверка частоты использования 1-3
                        if(($arr_ru_item[2] < 1) || ($arr_ru_item[2] > 3)){
                            $model->usage = 1;
                        }else{
                            $model->usage = trim($arr_ru_item[2]);
                        }

                        $model->usage_general = $model_eng->id;

                        //Проверка необходимости создавать связь
                        //если существует и английское и русское слово в базах,
                        //то проверяем существует ли для них связь в Dictionary
                        //Связь создается либо если небыло какого то из слов либо если небыло связи
                        if(($eng_id != false) && ($ru_id !=false)){
                            $items = $model->findAllByAttributes(array('id_eng' => $eng_id));
                            $flag = false;
                            foreach($items as $item){
                                if($item['id_ru'] == $ru_id){
                                    $flag = true;
                                }
                            }
                            if(!$flag){
                                if(!$model->save()){
                                    $str_preview = "Не правильно заполнены поля";
                                }
                            }
                        }else{
                            if(!$model->save()){
                                $str_preview = "Не правильно заполнены поля";
                            }
                        }
                    }
                }
                //Если текущий язык русский...
                else{
                    $str_preview = 'current_leng = ru';
                }
            }
        }
        $this->render('create',array(
            'model'=>$model,
            'str_preview' => $str_preview,
            'str_save' => $str_save,
            'eng_str' => $eng_word,
            'ru_str' => $ru_word,
            'save_ready' => $save_ready,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Dictionary']))
        {
            $model->attributes=$_POST['Dictionary'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }


    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model=new Dictionary('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Dictionary']))
            $model->attributes=$_GET['Dictionary'];

        $this->render('index',array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Dictionary the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Dictionary::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Dictionary $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='dictionary-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
