<?php
/* @var $this AuthController */
/* @var $model LoginForm */

$this->pageTitle = Yii::app()->name . ' - Login';
?>
<div class="content-wrap footer-pad">
    <div class='row-fluid' style="margin-top:50px;">
        <div class='span10 offset1'>
            <div class='row-fluid'>
                <div class='span6 offset3'>
                    <h2><?php echo CHtml::encode(Yii::t('core', 'Login Box')); ?></h2>
                    <?php $this->renderPartial('_form', array(
                        'model' =>  $model,
                    )) ?>
                </div>
            </div>
        </div>
    </div>
</div>
