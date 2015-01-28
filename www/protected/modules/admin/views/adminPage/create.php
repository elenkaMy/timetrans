<?php
/* @var $this AdminPageController */
/* @var $model Page */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Pages')    =>  array('index'),
    Yii::t('admin', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Page'), 'url' => array('index')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Create Page')); ?></h1>

<?php echo $this->renderPartial('form/_form', array('model' => $model)); ?>
