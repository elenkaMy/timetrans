<?php
/* @var $this AdminProductCategoryController */
/* @var $model ProductCategory */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Product Categories'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Product Category'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product Category'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Product Categories')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>