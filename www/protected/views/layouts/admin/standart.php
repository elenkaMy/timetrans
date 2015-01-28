<?php /* @var $this AdminController */ ?>
<!DOCTYPE>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="<?php echo Yii::app()->baseUrl ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo Yii::app()->baseUrl ?>/favicon.ico" type="image/x-icon">

    <?php Yii::app()->clientScript
            ->addAndRegisterPackage('admin_core', array(
                'baseUrl'       =>  Yii::app()->baseUrl,
                'js'            =>  array(
                    'js/admin/core.js',
                ),
                'css'           =>  array(
                    'css/admin.css'
                ),
            ))
    ; ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
    <!--[if lt IE 7]>
        <p class="chromeframe">
            <?php echo Yii::t('core', 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.') ?>
        </p>
    <![endif]-->

    <!-- Site or application content starts here -->

    <?php echo $content; ?>

    <div class="navbar navbar-inverse navbar-fixed-bottom">
        <div class="navbar-inner">
            <div class="row-fluid">
                <?php echo TbHtml::tag('div', array(
                    'textAlign' =>  TbHtml::TEXT_ALIGN_CENTER,
                    'class'     =>  'span10 offset1 navbar-text',
                ), 'Copyright &copy; '.CHtml::encode(Yii::app()->name).', '.date('Y')) ?>
            </div>
        </div>
    </div>

    <!-- //Site or application content ends here -->
</body>
</html>
