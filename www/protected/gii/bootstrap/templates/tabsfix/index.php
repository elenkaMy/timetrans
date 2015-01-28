<?php
/**
 * The following variables are available in this template:
 */
/* @var $this BootstrapCode */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->modelClass ?> */
/* @var $dataProvider CActiveDataProvider */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
    Yii::t('admin', '$label'),
);\n";
?>

$this->menu = array(
    array('label' => Yii::t('admin', 'Create <?php echo $this->modelClass; ?>'), 'url' => array('create')),
);
?>

<h1><?php echo '<?php' ?> echo CHtml::encode(Yii::t('admin', '<?php echo $label; ?>')); ?></h1>

<?php echo "<?php" ?> $this->renderPartial('_grid', array(
    'dataProvider'  =>  $dataProvider,
    'model'         =>  $model,
)); ?>