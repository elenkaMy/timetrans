<?php
/* @var $this AdminProductController */
/* @var $model Product */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Products') =>  array('index'),
    '#'.$model->id  =>  array('view', AdminProductController::GET_PARAM_NAME => $model->id),
    Yii::t('admin', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Product'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'Update Product'), 'url' => array('update', AdminProductController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'View Product'), 'url' => array('view', AdminProductController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete Product'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', AdminProductController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'))),
    array('label' => Yii::t('admin', 'View Page On Web Site'), 'url' => Yii::app()->createUrl('product/product/index', array('record' => $model)), 'linkOptions' => array('target' => '_blank')),
); ?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Update Product')); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('form/_form', array('model' => $model)); ?>
