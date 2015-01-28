<?php
/* @var $this AdminProductController */
/* @var $model Product */
/* @var $form ActiveForm */
?>

<?php $this->widget('application.widgets.FileUploader', array(
    'model' => $model,
    'attribute' =>  'pack_file_id',
)); ?>