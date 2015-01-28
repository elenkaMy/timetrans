<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Menu'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Menu'), 'url' => array('index')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Menu')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>