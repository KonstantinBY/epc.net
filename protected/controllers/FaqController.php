<?php

class FaqController extends Controller
{
	public function actionIndex()
	{
        $model = Faq::model()->findAll();

		$this->render('index', array('model' => $model));
	}

}