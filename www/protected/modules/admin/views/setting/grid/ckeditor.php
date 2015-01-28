<?php
/* @var $this CDataColumn */
/* @var $model Setting */
/* @var $row integer */

/* @var $formatter CFormatter */
$formatter = Yii::app()->format;
?>
<em>
    [content]<br/>
    Size: <?php echo $formatter->formatSize(
        function_exists('mb_strlen')
            ? mb_strlen($model->value, Yii::app()->charset)
            : strlen($model->value)
    ) ?>
</em>
