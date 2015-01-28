<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
?>
<h1>Create <?php echo $this->modelClass; ?></h1>

<?php echo "<?php \$this->renderPartial('_form', array('model' => \$model)); ?>"; ?>

