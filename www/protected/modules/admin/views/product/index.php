<?php
/* @var $this AdminProductController */
/* @var $model Product */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Products'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Product'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Products')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>