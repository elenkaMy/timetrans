<?php
/* @var $this UserController */
/* @var $model User */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Users')    =>  array('index'),
    '#'.$model->id  =>  array('view', UserController::GET_PARAM_NAME => $model->id),
    Yii::t('admin', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List User'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create User'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'View User'), 'url' => array('view', UserController::GET_PARAM_NAME => $model->id)),
); ?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Update User'));?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
