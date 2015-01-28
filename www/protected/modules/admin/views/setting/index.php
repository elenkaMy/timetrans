<?php
/* @var $this SettingController */
/* @var $model Setting */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Settings'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Setting'), 'url' => array('index')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Settings')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>