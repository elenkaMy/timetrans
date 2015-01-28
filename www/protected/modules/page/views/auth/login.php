<?php
/* @var $this AuthController */
/* @var $model LoginForm */
/* @var $form ActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
?>

<h2><?php echo CHtml::encode(Yii::t('core', 'Login Box')); ?></h2>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id'                        =>  'login-form',
    'enableClientValidation'    =>  true,
    'clientOptions'             =>  array(
        'validateOnSubmit'  =>  true,
    ),
    'htmlOptions'               =>  array(
        'class' =>  'form login',
    ),
)); ?>

    <div>
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php if ($model->hasErrors('username')): ?>
            <span class="error"><?php echo $model->getError('username'); ?></span>
        <?php endif; ?>
        <?php echo $form->textField($model, 'username', array('size' => 60, 'maxlength' => 255)); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php if ($model->hasErrors('password')): ?>
            <span class="error"><?php echo $model->getError('password'); ?></span>
        <?php endif; ?>
        <?php echo $form->passwordField($model, 'password'); ?>
    </div>

    <div>
        <?php echo $form->checkBox($model, 'rememberMe'); ?>
        <?php echo $form->labelEx($model, 'rememberMe'); ?>
        <?php if ($model->hasErrors('rememberMe')): ?>
            <span class="error"><?php echo $model->getError('rememberMe'); ?></span>
        <?php endif; ?>
    </div>

    <div>
        <input type="submit" value="<?php echo CHtml::encode(Yii::t('core', 'Login')) ?>" />
    </div>

<?php $this->endWidget(); ?>