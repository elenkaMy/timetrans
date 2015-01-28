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
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
$tLabel = "Yii::t('admin', '$label')";
echo "\$this->breadcrumbs = array(
    ".str_pad($tLabel, max(round((strlen($tLabel) + 1) / 4) * 4, 16))."=>  array('index'),
    '#'.\$model->{$nameColumn}  =>  array('view', ".$this->controllerClass."::GET_PARAM_NAME => \$model->{$this->tableSchema->primaryKey}),
    Yii::t('admin', 'Update'),
);\n";
?>

$this->menu = array(
    array('label' => Yii::t('admin', 'List <?php echo $this->modelClass; ?>'), 'url' => array('index')),
    array('label' => Yii::t('admin', 'Create <?php echo $this->modelClass; ?>'), 'url' => array('create')),
    array('label' => Yii::t('admin', 'View <?php echo $this->modelClass; ?>'), 'url' => array('view', <?php echo $this->controllerClass ?>::GET_PARAM_NAME => $model-><?php echo $this->tableSchema->primaryKey; ?>)),
    array('label' => Yii::t('admin', 'Update <?php echo $this->modelClass; ?>'), 'url' => array('update', <?php echo $this->controllerClass ?>::GET_PARAM_NAME => $model-><?php echo $this->tableSchema->primaryKey; ?>)),
    array('label' => Yii::t('admin', 'Delete <?php echo $this->modelClass; ?>'), 'url' => '#', 'linkOptions' => array('submit' => array('delete', <?php echo $this->controllerClass ?>::GET_PARAM_NAME => $model-><?php echo $this->tableSchema->primaryKey; ?>), 'confirm' => Yii::t('zii','Are you sure you want to delete this item?'))),
//    array('label' => Yii::t('admin', 'View <?php echo $this->modelClass; ?> On Web Site'), 'url' => Yii::app()->createUrl('model route', array('record' => $model)), 'linkOptions' => array('target' => '_blank')),
); ?>

<h1><?php echo '<?php'?> echo CHtml::encode(Yii::t('admin', 'Update <?php echo $this->modelClass ?>')); ?><?php echo " <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model' => \$model)); ?>"; ?>

