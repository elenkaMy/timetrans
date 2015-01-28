<?php
/* @var $this SettingController */
/* @var $model Setting */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Settings') =>  array('index'),
    '#'.$model->id  =>  array('view', SettingController::GET_PARAM_NAME => $model->id),
    Yii::t('admin', 'Update'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List Setting'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'View Setting'), 'url' => array('view', SettingController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Update Setting'), 'url' => array('view', SettingController::GET_PARAM_NAME => $model->id)),
); ?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Update Setting')); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
