<?php
/* @var $this DefaultController */
$this->layout = '//layouts/admin/column1';
Yii::import('bootstrap.widgets.TbMenu');

$items = $this->createWidget('application.widgets.MainAdminMenu')->items;
if (count($items)) {
    array_shift($items);
}
?>
<h1><?php echo CHtml::encode(Yii::t('admin', 'Welcome to Admin Panel')); ?></h1>
<div class="row-fluid">&nbsp;</div>
<div class="row-fluid">
    <div class="span3">
        <blockquote>
            <?php 
                $this->widget('TbMenu', array(
                    'type'      =>  TbMenu::TYPE_TABS,
                    'items'     =>  $items,
                    'stacked'   =>  true,
                ));
            ?>
        </blockquote>
    </div>
</div>
