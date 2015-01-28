<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/admin/menu-items/form.js');
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'                    =>  'model-form',
    'enableAjaxValidation'  =>  false,
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
)); ?>
<?php
/* @var $form TbActiveForm */
?>

    <p class="help-block"><?php echo Yii::t('admin', 'Fields with {required} are required.', array('{required}' => CHtml::tag('span', array('class' => 'required'), '*'))) ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'pills',
        'tabs' => array(
            array('label' => Yii::t('admin','Basic'), 'content' => $this->renderPartial('form/_basic', array('model' => $model, 'form' => $form), true), 'active' => true),
            array('label' => Yii::t('admin','Html Options'), 'content' => $this->renderPartial('form/_html-options', array('model' => $model, 'form' => $form), true)),
        ),
    )); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    =>  'submit',
            'type'          =>  'success',
            'label'         =>  Yii::t('admin', $model->isNewRecord ? 'Save and View' : 'Save'),
        )); ?>
        <input class="btn btn-primary" name="another-button" type="submit" value="<?php echo Yii::t('admin', $model->isNewRecord ? 'Save and Create' : 'Apply');?>"/>
    </div>

<?php $this->endWidget(); ?>
