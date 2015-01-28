<?php
/* @var $this AdminProductCategoryController */
/* @var $model ProductCategory */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Product Categories')   =>  array('index'),
    '#'.$model->id,
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List Product Category'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product Category'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'Update Product Category'), 'url' => array('update', AdminProductCategoryController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'View Product Category'), 'url' => array('view', AdminProductCategoryController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete Product Category'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', AdminProductCategoryController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'), 'visible' => $model->fixed_name === null)),
    array('label' => Yii::t('admin', 'View Page On Web Site'), 'url' => Yii::app()->createUrl('product/category/index', array('record' => $model)), 'linkOptions' => array('target' => '_blank')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'View Product Category')); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'          =>  $model,
    'attributes'    =>  array(
        'id',
//        array(
//            'name' => 'parent_category_id',
//            'value' => $model->parentCategory ? $model->parentCategory->category_name : '',
//        ),
        'category_name',
        array(
            'type'  =>  'image',
            'name'  =>  Yii::t('admin', 'File'),
            'value' =>  $this->fileHelper->productCategoryContext->getWebPath($model->file->path, 'admin'),
            'visible' => $model->file,
        ),
        'alias',
        'content',
        'short_content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'position',
        'created_at',
        'updated_at',
    ),
)); ?>
