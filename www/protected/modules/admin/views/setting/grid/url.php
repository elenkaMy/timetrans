<?php
/* @var $this CDataColumn */
/* @var $model Setting */
/* @var $row integer */

/* @var $formatter CFormatter */
$formatter = Yii::app()->format;
?>
<?php echo CHtml::link(CHtml::encode($model->value), $model->value, array(
    'target'    =>  '_blank',
)) ?>
