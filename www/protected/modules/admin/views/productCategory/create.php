<?php
/* @var $this AdminProductCategoryController */
/* @var $model ProductCategory */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Product Categories')   =>  array('index'),
    Yii::t('admin', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Product Category'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product Category'), 'url' => array('create')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Create Product Category')); ?></h1>

<?php echo $this->renderPartial('form/_form', array('model' => $model)); ?>
