<?php
/* @var $this SettingController */
/* @var $model Setting */

/* @var $formatter CFormatter */
$formatter = Yii::app()->format;

return $formatter->formatNtext($model->value);
