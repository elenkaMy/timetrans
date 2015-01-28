<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
/* @var $menu Menu */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Menu') => array('menu/index'),
    Yii::t('admin', 'Menu Items') => Yii::app()->createUrl('admin/menuItem/index', array('menu_id' => $menu->id)),
    Yii::t('admin', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List MenuItem'), 'url' => Yii::app()->createUrl('admin/menuItem/index', array('menu_id' => $menu->id))),

);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Create MenuItem')); ?></h1>

<?php echo $this->renderPartial('form/_form', array(
    'model' => $model,
)); ?>
