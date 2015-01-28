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
<?php echo "<?php \$form = \$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'                    =>  '" . $this->class2id($this->modelClass) . "-form',
    'enableAjaxValidation'  =>  false,
)); ?>\n"; ?>
<?php echo "<?php\n"; ?>
/* @var $form TbActiveForm */
?>

    <p class="help-block"><?php echo '<?php' ?> echo Yii::t('admin', 'Fields with {required} are required.', array('{required}' => CHtml::tag('span', array('class' => 'required'), '*'))) ?></p>

    <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php
    if ($column->autoIncrement) {
        continue;
    }
?>
    <?php echo "<?php echo " . str_replace(array(',', '=>'), array(', ', ' => '), $this->generateActiveRow($this->modelClass, $column)) . "; ?>\n"; ?>

<?php endforeach; ?>
    <div class="form-actions">
        <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'    =>  'submit',
                'type'          =>  'success',
                'label'         =>  Yii::t('admin', \$model->isNewRecord ? 'Save and View' : 'Save'),
            )); ?>\n"; ?>

        <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'    =>  'submit',
                'type'          =>  'primary',
                'label'         =>  Yii::t('admin', \$model->isNewRecord ? 'Save and Create' : 'Apply'),
                'htmlOptions'   => array('name' => 'another-button'),
            )); ?>\n"; ?>
    </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
