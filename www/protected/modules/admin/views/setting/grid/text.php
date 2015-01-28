<?php
/* @var $this CDataColumn */
/* @var $model Setting */
/* @var $row integer */

/* @var $formatter CFormatter */
$formatter = Yii::app()->format;
?>
<?php echo $formatter->formatNtext($model->value) ?>
