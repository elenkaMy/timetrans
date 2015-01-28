<?php
/* @var $this SettingController */
/* @var $model Setting */
/* @var $form TbActiveForm */
?>
<?php echo $form->labelEx($model, 'value'); ?>
<?php if ($model->hasErrors('value')): ?>
    <span class="error"><?php echo $model->getError('value'); ?></span>
<?php endif; ?>
<?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
    'model' => $model,
    'attribute' => 'value',
    'height' => '300px',
    'editorTemplate' => 'full',
)); ?>
