<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/epc.css" media="screen, projection" />

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/epc.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div id = "maket">
    <div id = "container">
        <div id = "header">
            <div id = logo><a href = "<?php echo Yii::app()->request->baseUrl; ?>"></a></div>
            <div id = logo_title></div>
            <div id = login>
                <form title="login">
                    <label>Name<input type="text"></label>
                    <label>Password<input type="text"></label>
                </form>
            </div>
        </div>
        <div id = "menu">
            <div id = "btn_news"><a href = "<?php echo Yii::app()->request->baseUrl; ?>/" ></a></div>
            <div id = "btn_dictionary" class = "btn_menu"><a href = "<?php echo Yii::app()->request->baseUrl; ?>/dictionaryPage"></a></div>
            <div id = "btn_often" class = "btn_menu"><a href = "<?php echo Yii::app()->request->baseUrl; ?>/dictionaryOffen"></a></div>
            <div id = "btn_picture" class = "btn_menu"><a href = "javascript:void(0);"></a></div>
            <div id = "btn_print" class = "btn_menu"><a href = "<?php echo Yii::app()->request->baseUrl; ?>/print"></a></div>
            <div id = "btn_faq" class = "btn_menu"><a href = <?php echo Yii::app()->request->baseUrl; ?>"/faq"></a></div>
            <div id = "btn_account" class = "btn_menu"><a href = "javascript:void(0)"></a></div>
        </div>
        <div id = "middle">
            <div id = "left"><?php echo $content;?></div>
            <div id = "right"></div>
        </div>
    </div>
    <div id = "footer"> </div>
</div>
</body>

</body>
</html>
