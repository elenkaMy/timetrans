<?php 
/* @var $this ProductController */
/* @var $form ActiveForm */
/* @var $model SendForm */
 ?>
 <?php echo CHtml::beginForm(Yii::app()->createUrl('product/product/send', array(
    )), 'POST', array('id' => 'send-form')); ?>
    <div class="reserv_line">
        <?php if ($model->hasErrors('telephone')): ?>
        <div class="errors">
            <span class="error"><?php echo $model->getError('telephone'); ?></span>
        </div>
        <?php endif; ?>
        <div class="reserv_line_text">Ваш номер телефона*</div>
        <div class="reserv_line_input">
            <?php echo CHtml::activeTextField($model, 'telephone', array()); ?>
        </div>
    </div>
    <div class="reserv_notice">Поля, отмеченые *, обязательны для заполнения</div>
    <div class="form-send-button" >
        <a href="#" data-url='<?php echo Yii::app()->createUrl('product/product/send');?>' class="mailButton" style="text-decoration: none;"><span class="button-send">ЗАКАЗАТЬ ЗВОНОК</span></a>
    </div>
<?php echo CHtml::endForm(); ?>

