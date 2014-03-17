<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ekstazi
 * Date: 05.07.13
 * Time: 23:56
 * To change this template use File | Settings | File Templates.
 */
Yii::setPathOfAlias('tinymce',dirname(__FILE__));

class TMCEInput extends CInputWidget
{
	/**
	 * Modes use to set base options, you can override it options parameter
	 */

	/**
	 * Base toolbars mode for TMCE
	 */
	const MODE_BASIC='basic';
	/**
	 * Advanced toolbars mode
	 */
	const MODE_ADVANCED='advanced';
	/**
	 * No default toolbars, ie user mode
	 */
	const MODE_CUSTOM='custom';

	/**
	 * Alias for extension package
	 */
	const PACKAGE_ALIAS='tinymce';

	/**
	 * Default options presets, merged into user options
	 * @var array
	 */
	public static $defaults=array(
		self::MODE_BASIC=>array(
			'plugins'=>array(
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste"
			),
			'toolbar'=> "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |
				bullist numlist outdent indent | link image",
		),
		self::MODE_ADVANCED=>array(
    		'plugins'=>array(
				"advlist autolink lists link image charmap print preview hr anchor pagebreak",
				"searchreplace wordcount visualblocks visualchars code fullscreen",
				"insertdatetime media nonbreaking save table contextmenu directionality",
				"emoticons template paste textcolor"
			),
    		'toolbar1'=>"insertfile undo redo | styleselect | bold italic |
    			alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    		'toolbar2'=>"print preview media | forecolor backcolor emoticons",
    		'image_advtab'=>true,
		),
		self::MODE_CUSTOM=>array(

		)
	);

	/**
	 * Mode preset. You can use basic, advanced or custom.
	 * @var int
	 */
	public $mode=self::MODE_BASIC;

	/**
	 * Current language. If false use default, if null then autodetect
	 * @var bool
	 */
	public $language;

	/**
	 * If string use as tagName for inline input. Otherwise use standart textarea
	 * @var bool
	 */
	public $inline=false;

	/**
	 * Hidden options. Used for hidden field in inline mode
	 * @var array
	 */
	public $hiddenOptions=array();

	/**
	 * TMCE options
	 * @var
	 */
	public $options=array();

	/**
	 * @var array supported languages
	 */
	private static $supportedLanguages = array(
		'ar', 'de', 'fi', 'id', 'lv', 'ro', 'th_TH',
		'bg_BG', 'de_AT', 'fo', 'it', 'nb_NO', 'ru', 'tr_TR',
		'bs', 'el', 'fr_FR', 'ja', 'nl', 'si_LK', 'ug',
		'ca', 'es', 'gl', 'ka_GE', 'pl', 'sk', 'uk',
		'cs', 'et', 'he_IL', 'ko_KR', 'pt_BR', 'sl_SI', 'vi_VN',
		'cy', 'eu', 'hr', 'lb', 'pt_PT', 'sr', 'zh_CN',
		'da', 'fa', 'hu_HU', 'lt', 'sv_SE', 'zh_TW',
	);

	protected function registerClientScripts()
	{
		list(,$id)=$this->resolveNameID();
		/** @var $cs CClientScript */
		$cs=\Yii::app()->clientScript;
		$cs->addPackage(self::PACKAGE_ALIAS,array(
	      'basePath'=>'tinymce.assets',
	      'js'=>array(
			  'tinymce/tinymce.min.js',
			  'tinymce/jquery.tinymce.min.js'
		  ),
	      'depends'=>array('jquery'),
		));
		if($this->options['hidden_input_id']){
			$cs->packages[self::PACKAGE_ALIAS]['js'][]='jquery.inlineTMCE.js';
			$method='inlineTMCE';
		}else
			$method='tinymce';
		$cs->registerPackage(self::PACKAGE_ALIAS);
		$cs->registerScript(self::PACKAGE_ALIAS.'_'.$id,"$('#$id').$method(".CJavaScript::encode($this->options).")");
	}

	/**
	 * Prepare options for js and input
	 */
	protected function prepareOptions()
	{
		list($name,$id)=$this->resolveNameID();

		$this->options=array_merge($this->options,self::$defaults[$this->mode]);
		$this->htmlOptions['id']=$id;
		// prepare inline mode if enabled
		$this->options['inline']=!empty($this->inline);
		if(!empty($this->inline)){
			$id="hidden_$id";
			// first auto options, then user
			$this->hiddenOptions=array_merge(compact('name','id'),$this->hiddenOptions);
			// remove unused options for inline element if preset
			unset($this->htmlOptions['name']);
			$this->options['hidden_input_id']="#$id";
		}
		$this->prepareLanguage();

	}

	protected function prepareLanguage()
	{
		if ($this->language === false)
			return;
		if (is_null($this->language))
			$this->language = Yii::app()->language;

		// only supported language
		if (!in_array($this->language, self::$supportedLanguages)) {
			$found = false;
			foreach (self::$supportedLanguages as $i) {
				if (strpos($this->language, $i) === false)
					continue;
				$found = $i;
			}
			$this->language = $found ? $found : 'en';
		}
		$this->options['language'] = $this->language;
	}

	public function init()
	{
		$this->prepareOptions();
		$this->registerClientScripts();
	}

	public function run()
	{
		if(empty($this->inline))
		{
			echo $this->hasModel() ?
				CHtml::activeTextArea($this->model,$this->attribute,$this->htmlOptions) :
				CHtml::textArea($this->name,$this->value,$this->htmlOptions);
		}else {
			// for hidden field
			echo CHtml::tag($this->inline,$this->htmlOptions,'kkk');

			echo $this->hasModel() ?
				CHtml::activeHiddenField($this->model,$this->attribute,$this->hiddenOptions) :
				CHtml::hiddenField($this->name,$this->value,$this->hiddenOptions);
		}


	}
}

