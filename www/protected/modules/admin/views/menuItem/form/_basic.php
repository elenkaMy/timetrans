<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
/* @var $form TbActiveForm */
?>

<?php echo $form->dropDownListRow($model, 'parent_item_id', array('' => '') + $model->getAllowedParentsForList(), array('class' => 'span5')); ?>
<?php echo $form->textFieldRow($model, 'item_name', array('class' => 'span5', 'maxlength' => 255)); ?>

<?php echo $form->dropDownListRow($model, 'item_type', Yii::app()->menuItemHelper->types, array(
    'class' => 'span5 menu-item-type',
)); ?>

<?php echo $form->label($model, 'value', array('class' => 'value-label')); ?>
<?php foreach(Yii::app()->menuItemHelper->types as $shortTypeName => $type):?>
    <div class="rendered-item-type rendered-item-type-<?php echo $shortTypeName ?>" data-type="<?php echo $shortTypeName ?>">
        <?php echo $type->renderFormElement($model);?>
    </div>
<?php endforeach;?>

<?php echo $form->hiddenField($model, 'value', array('class' => 'span5 menu-item-value')); ?>

<?php echo $form->numberFieldRow($model, 'position', array('class' => 'span5')); ?>
