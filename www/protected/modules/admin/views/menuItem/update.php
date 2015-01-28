<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Menu') => array('menu/index'),
    Yii::t('admin', 'Menu Items')   =>  Yii::app()->createUrl('admin/menuItem/index', array('menu_id' => $model->menu_id)),
    '#'.$model->id  =>  array('view', MenuItemController::GET_PARAM_NAME => $model->id),
    Yii::t('admin', 'Update'),
);

$this->menu=array(
    array('label' => Yii::t('admin', 'List MenuItem'), 'url' => Yii::app()->createUrl('admin/menuItem/index', array('menu_id' => $model->menu_id))),
    array('label' => Yii::t('admin', 'View MenuItem'), 'url' => array('view', MenuItemController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Create MenuItem'), 'url' => Yii::app()->createUrl('admin/menuItem/create', array('menu_id' => $model->menu_id))),
    array('label' => Yii::t('admin', 'Update MenuItem'), 'url' => array('update', MenuItemController::GET_PARAM_NAME => $model->id)),
    array('label' => Yii::t('admin', 'Delete MenuItem'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', MenuItemController::GET_PARAM_NAME => $model->id), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'))),
    array('label' => Yii::t('admin', 'Menu'), 'url' => array('menu/index')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Update MenuItem')); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('form/_form', array(
    'model' => $model,
)); ?>
