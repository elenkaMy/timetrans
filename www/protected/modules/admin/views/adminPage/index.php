<?php
/* @var $this AdminPageController */
/* @var $model Page */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Pages'),
);


$this->menu = array(
    array('label' => Yii::t('admin', 'Create Page'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Pages')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>