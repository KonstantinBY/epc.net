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
				'actions'=>array('admin','delete','create','update'),
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

    public function wordToArray($items){
        foreach($items as $item){
            //$ru_str .= $item_ru;
            if((!empty($item[1]))){
                for($i = 0; $i <= count($item)-1; $i++){
                    $items_2 = preg_split("/[;]/", $item);
                    $arr_items[] = $items_2;
                }
            }
        }
        return $arr_items;
    }

    public function preview(){

    }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
/*
		if(isset($_POST['Dictionary'] == 'save'))
		{
			$model->attributes=$_POST['Dictionary'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
*/
        if($_POST['dictionary'] == 'create')
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
                        $model=new Dictionary;

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
                        if(($arr_ru_item[1] < 1) || ($arr_ru_item[1] > 14)){
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
                                if($item[id_ru] == $ru_id){
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
                    print_r($arr_eng);
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
