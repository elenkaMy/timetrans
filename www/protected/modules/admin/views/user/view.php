<?php
/* @var $this UserController */
/* @var $model User */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Users') =>  array('index'),
    '#'.$model->id,
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List User'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create User'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'Update User'), 'url' => array('update', UserController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete User'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', UserController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'))),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'View User')) ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
    'data'          =>  $model,
    'attributes'    =>  array(
        'id',
        'username',
        'email',
        'is_admin'  =>  array(
            'label' =>  $model->getAttributeLabel('is_admin'),
            'value' =>  Yii::t('yii', $model->is_admin ? 'Yes' : 'No'),
        ),
        'created_at',
        'updated_at',
    ),
)); ?>
