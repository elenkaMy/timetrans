<?php
/* @var $this AdminProductCategoryController */
/* @var $model ProductCategory */
/* @var $form ActiveForm */
?>

<?php echo $form->textFieldRow($model, 'seo_title', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->textAreaRow($model, 'seo_description', array('rows' => 6,  'cols' => 50,  'class' => 'span8')); ?>

<?php echo $form->textAreaRow($model, 'seo_keywords', array('rows' => 6,  'cols' => 50,  'class' => 'span8')); ?>
