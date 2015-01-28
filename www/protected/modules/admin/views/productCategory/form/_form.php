<?php
/* @var $this AdminProductCategoryController */
/* @var $model ProductCategory */
?>
<?php $form = $this->beginWidget('application.widgets.ActiveForm',array(
    'id'                    =>  $formId = 'product-category-form',
    'enableAjaxValidation'  =>  false,
)); ?>
<?php
/* @var $form ActiveForm */
?>
    <p class="help-block"><?php echo Yii::t('admin', 'Fields with {required} are required.', array('{required}' => CHtml::tag('span', array('class' => 'required'), '*'))) ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'pills',
        'placement' => 'above',
        'tabs' => array(
            array('label' => Yii::t('admin', 'Basic'), 'content' => $this->renderPartial('form/_basic', array('model' => $model, 'form' => $form), true), 'active' => true),
            array('label'=>Yii::t('admin', 'Seo'), 'content' => $this->renderPartial('form/_seo', array('model' => $model, 'form' => $form), true)),
        ),
    )); ?>


    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'    =>  'submit',
            'type'          =>  'success',
            'label'         =>  Yii::t('admin', $model->isNewRecord ? 'Save and View' : 'Save'),
        )); ?>
        <input class="btn btn-primary" name="another-button" type="submit" value="<?php echo Yii::t('admin', $model->isNewRecord ? 'Save and Create' : 'Apply');?>"/>

        <?php $this->widget('application.widgets.PreviewWidget', array(
            'url' => $model->isNewRecord ? $this->createUrl('previewOnCreate') : $this->createUrl('previewOnUpdate', array('product_category_id' => $model->id)),
            'formId' => $formId,
        ));
        ?>
    </div>

<?php $this->endWidget(); ?>

