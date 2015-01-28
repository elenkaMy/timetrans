<?php
/* @var $this SettingController */
/* @var $model Setting */

/* @var $formatter CFormatter */
$formatter = Yii::app()->format;

return CHtml::link(CHtml::encode($model->value), $model->value, array(
    'target'    =>  '_blank',
));
