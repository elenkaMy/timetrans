<?php
/**
 * The following variables are available in this template:
 */
/* @var $this BootstrapCode */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
$tLabel = "Yii::t('admin', '$label')";
echo "\$this->breadcrumbs = array(
    ".str_pad($tLabel, round((strlen($tLabel) + 2) / 4) * 4)."=>  array('index'),
    Yii::t('admin', 'Create'),
);\n";
?>

$this->menu = array(
    array('label' => Yii::t('admin', 'List <?php echo $this->modelClass; ?>'), 'url' => array('index')),
);
?>

<h1><?php echo '<?php' ?> echo CHtml::encode(Yii::t('admin', 'Create <?php echo $this->modelClass; ?>')); ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model' => \$model)); ?>"; ?>

