<?php
/* @var $this SettingController */
/* @var $model Setting */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Settings') =>  array('index'),
    '#'.$model->id,
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List Setting'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'View Setting'), 'url' => array('view', SettingController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Update Setting'), 'url' => array('update', SettingController::GET_PARAM_NAME => $model->id)),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'View Setting')); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'          =>  $model,
    'attributes'    =>  array(
        'id',
        'label',
        array(
            'name'  =>  'value',
            'type'  =>  'raw',
            'value' =>  require(__DIR__."/view/$model->setting_type.php"),
        ),
        array(
            'name'  => 'setting_type',
            'value' => Yii::t('setting', $model->setting_type),
        ),
        array(
            'name'  => 'can_be_empty',
            'value' => Yii::t('setting', $model->can_be_empty ? 'Yes' : 'No'),
        ),
        'created_at',
        'updated_at',
    ),
)); ?>
