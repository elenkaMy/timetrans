<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
/* @var $dataProvider CActiveDataProvider */
/* @var $menu Menu */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Menu') => array('menu/index'),
    Yii::t('admin', 'Menu Items'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List MenuItem'), 'url' => Yii::app()->createUrl('admin/menuItem/index', array('menu_id' => $menu->id))),
    array('label' => Yii::t('admin', 'Create MenuItem'), 'url' => Yii::app()->createUrl('admin/menuItem/create', array('menu_id' => $menu->id))),
    array('label' => Yii::t('admin', 'Menu'), 'url' => array('menu/index')),
);
?>

    <h1><?php echo CHtml::encode(Yii::t('admin', 'Menu Items')); ?></h1>

<?php $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
    'menu'          =>  $menu,
)); ?>