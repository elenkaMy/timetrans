<?php
/* @var $this UserController */
/* @var $model User */
?>
<?php
$this->breadcrumbs = array(
    Yii::t('admin', 'Users') =>  array('index'),
    Yii::t('admin', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('admin', 'List User'), 'url' => array('index')),
);
?>

<h1><?php echo CHtml::encode(Yii::t('admin', 'Create User' )); ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
