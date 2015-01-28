<?php
/* @var $this AuthController */
/* @var $model LoginForm */
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'                    =>  'login-form',
    'enableAjaxValidation'  =>  false,
)); ?>
<?php /* @var $form TbActiveForm */ ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'username', array('class' => 'span12', 'maxlength' => 255)); ?>

    <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span12', 'maxlength' => 255)); ?>

    <?php echo $form->checkBoxRow($model, 'rememberMe'); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'    =>  'submit',
                'type'          =>  'primary',
                'label'         =>  Yii::t('core', 'Login'),
            )); ?>
    </div>

<?php $this->endWidget(); ?>