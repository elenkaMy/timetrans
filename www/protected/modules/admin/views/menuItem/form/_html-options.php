<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
/* @var $form TbActiveForm */
?>

<?php echo $form->textAreaRow($model, 'item_options', array('rows' => 4,  'cols' => 40,  'class' => 'span6')); ?>
<?php echo $form->textAreaRow($model, 'link_options', array('rows' => 4,  'cols' => 40,  'class' => 'span6')); ?>

<p><?php echo Yii::t('admin', 'Example');?></p>
<textarea rows="4" cols="40" disabled class="span6">id = id<?php echo "\n";?>class = class<?php echo "\n";?>data-attribute = data-attribute</textarea>