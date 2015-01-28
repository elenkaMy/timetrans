<?php
/* @var $this UserController */
/* @var $model User */
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'                    =>  'user-form',
    'enableAjaxValidation'  =>  false,
)); ?>
<?php
/* @var $form TbActiveForm */
?>

<p class="help-block"><?php echo Yii::t('admin', 'Fields with {required} are required.', array('{required}' => CHtml::tag('span', array('class' => 'required'), '*'))) ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->passwordFieldRow($model, 'new_password', array('class' => 'span5', 'maxlength' => 255)); ?>
    <?php echo $form->passwordFieldRow($model, 'new_password_repeat', array('class' => 'span5', 'maxlength' => 255)); ?>

    <?php echo $form->checkBoxRow($model, 'is_admin'); ?>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'    =>  'submit',
                'type'          =>  'primary',
                'label'         =>  Yii::t('admin', $model->isNewRecord ? 'Create' : 'Save'),
            )); ?>
    </div>

<?php $this->endWidget(); ?>
