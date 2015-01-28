<?php
/* @var $this AdminPageController */
/* @var $model Page */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Pages')=>  array('index'),
    '#'.$model->id,
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List Page'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create Page'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'Update Page'), 'url' => array('update', AdminPageController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'View Page'), 'url' => array('view', AdminPageController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete Page'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', AdminPageController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?')), 'visible' => $model->fixed_name === null),
    array('label' => Yii::t('admin', 'View Page On Web Site'), 'url' => Yii::app()->createUrl('page/page/index', array('record' => $model)), 'linkOptions' => array('target' => '_blank')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'View Page')); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'          =>  $model,
    'attributes'    =>  array(
        'id',
        array(
            'name' => 'parent_page_id',
            'value' => $model->parentPage ? $model->parentPage->page_name : '',
        ),
        'page_name',
        array(
            'type'  =>  'raw',
            'name'  =>  'alias',
            'value' =>  CHtml::link(
                CHtml::encode($model->alias),
                Yii::app()->createUrl('page/page/index', array(
                    'record' => $model,
                )),
                array(
                    'target' => "_blank",
                )
            ),
        ),
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
