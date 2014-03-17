tmce-editor-widget
==================

Tinymce jquery plugin widget for yii
How to install.
1) Put tinymce 4 into assets/tinymce folder
2) For multilanguage support put language pack into assets/tinymce folder.
How to use
View:
```php
$this->widget('ext.tmce-widget.TMCEInput',array(
	'model'=>$model,
	'attribute'=>'attribute',
	// or
	// name=>name, value=>value


	// you can also use tinymce preset. For more info see source of TMCEInput
	// 'mode'=>'advanced'
	// By default use basic preset

	// This option start tmce in inline mode. This extension use hidden field for emulating normal behavior of tmce
	// 'inline'=>true


	// Use this parameter for i18n support. By default false. Means use english. If null use app language.
	// language=>'ru'
	//
));
```
