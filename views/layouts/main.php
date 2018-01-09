<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="ru" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div class="container" id="page">
            <div class="header">
                <div class="section">
                    <div class="sub-section clearfix">
                        <a class="logo" href="http://www.wearesputnik.com<?php echo Yii::app()->request->baseUrl; ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png"> Байки из склепа</a>
                            <?php $this->widget('zii.widgets.CMenu',array(
                                    'htmlOptions' => array( 'class' => 'main-menu'),
                                    'items'=>array(
                                        array('label'=>'Login<span class="active-line">&nbsp;</span>', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                                        array('label'=>'Logout<span class="active-line">&nbsp;</span>', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                                        array('label'=>'List Books<span class="active-line">&nbsp;</span>', 'url'=>array('/books/index'), 'visible'=>!Yii::app()->user->isGuest),
                                        //array('label'=>'New book<span class="active-line">&nbsp;</span>', 'url'=>array('/books/create'), 'visible'=>!Yii::app()->user->isGuest),
                                        array('label'=>'New book<span class="active-line">&nbsp;</span>', 'url'=>array('/books/createtxt'), 'visible'=>!Yii::app()->user->isGuest),
                                        array('label'=>'Admin New Book<span class="active-line">&nbsp;</span>', 'url'=>array('/books/admin'), 'visible'=>  UserModule::isAdmin()),
                                    ),
                                'encodeLabel' => false,
                            )); 
                        ?>
                    </div>	
                </div>
            </div><!-- mainmenu -->
            <div class="sub-section clearfix">
                <div class="content">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </body>
</html>
