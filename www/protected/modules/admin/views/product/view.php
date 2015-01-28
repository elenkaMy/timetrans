<?php
/* @var $this AdminProductController */
/* @var $model Product */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Products') =>  array('index'),
    '#'.$model->id,
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List Product'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Product'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'Update Product'), 'url' => array('update', AdminProductController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'View Product'), 'url' => array('view', AdminProductController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete Product'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', AdminProductController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'))),
    array('label' => Yii::t('admin', 'View Page On Web Site'), 'url' => Yii::app()->createUrl('product/product/index', array('record' => $model)), 'linkOptions' => array('target' => '_blank')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'View Product')); ?> #<?php echo $model->id; ?></h1>
<?php $self = $this;?>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'          =>  $model,
    'attributes'    =>  array(
        'id',
        array(
            'name' => 'category_id',
            'value' => $model->category->category_name,
        ),
        'product_name',
        'article',
        array(
            'type'  =>  'image',
            'name'  =>  Yii::t('admin', 'File'),
            'value' =>  $this->fileHelper->productContext->getWebPath($model->file->path, 'admin'),
            'visible' => $model->file,
        ),
//        array(
//            'type'  => 'raw',
//            'name'  => 'pack_file_id',
//            'value' => TbHtml::carousel(array_map(function(File $picture) use ($self) {
//                return array(
//                    'image' => $self->fileHelper->productContext->getWebPath($picture, 'small'),
//                    'label' => $picture->file,
//                );
//            }, $model->pack_file_id ? $model->packFile->files : array()), array (   
//            )
//        )
//        ),
        'alias',
        'content',
        'short_content',
        'price',
        'seo_title',
        'seo_description',
        'seo_keywords',
         array(
             'name' => 'visible',
             'value' => $model->visible ? 'Да' : 'Нет',
         ),
        'position',
        'created_at',
        'updated_at',
    ),
)); ?>
<?php if ($model->packFile):?>
    <h4>Изображения</h4>
    <?php foreach($model->packFile->files as $file):?>
        <img src="<?php echo $this->fileHelper->productContext->getWebPath($file->path, 'admin');?>">
    <?php endforeach;?>
<?php endif;?>