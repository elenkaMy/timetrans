<?php
/* @var $this UserController */
/* @var $model User */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Users'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'Create User'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Users')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>