<?php
/* @var $this SettingController */
/* @var $model Setting */
/* @var $form TbActiveForm */
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'                    =>  'setting-form',
    'enableAjaxValidation'  =>  false,
)); ?>
<p class="help-block"><?php echo Yii::t('admin', 'Fields with {required} are required.', array('{required}' => CHtml::tag('span', array('class' => 'required'), '*'))) ?></p>
<?php $this->renderPartial('inputs/'.$model->setting_type, array(
    'model' => $model,
    'form'  => $form,
));?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'    =>  'submit',
        'type'          =>  'success',
        'label'         =>  Yii::t('admin', 'Apply'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
