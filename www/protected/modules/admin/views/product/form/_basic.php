<?
/* @var $this AdminProductController */
/* @var $model Product */
/* @var $form ActiveForm */
?>

<?php echo $form->dropDownListRow($model, 'category_id', array('' => '') + ProductCategory::model()->getAllCategories(), array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'product_name', array('class' => 'span5 russian-text', 'maxlength' => 255)); ?>

<?php echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->aliasFieldRow($model, 'alias', '.russian-text', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php $this->widget('application.widgets.FileUploader', array(
    'model' => $model,
    'attribute' =>  'file_id',
)); ?>

<?php echo $form->labelEx($model, 'content');?>
<?php if ($model->hasErrors('content')): ?>
    <span class="error"><?php echo $model->getError('content'); ?></span>
<?php endif; ?>
<?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
    'model' => $model,
    'attribute' => 'content',
    'language' => 'ru',
    'height' => '300px',
    'editorTemplate' => 'full',
)); ?><br>

<?php echo $form->labelEx($model, 'short_content');?>
<?php if ($model->hasErrors('short_content')): ?>
    <span class="error"><?php echo $model->getError('short_content'); ?></span>
<?php endif; ?>
<?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
    'model' => $model,
    'attribute' => 'short_content',
    'language' => 'ru',
    'height' => '150px',
    'editorTemplate' => 'full',
)); ?><br>

<?php echo $form->textFieldRow($model, 'price', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->numberFieldRow($model, 'position', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->checkBoxRow($model, 'visible'); ?>
