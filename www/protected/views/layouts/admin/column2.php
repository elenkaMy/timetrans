<?php /* @var $this AdminController */ ?>
<?php $this->beginContent('//layouts/admin/main'); ?>
<div class='row-fluid'>
    <div class="span9">
        <div id="content" class="content">
            <?php echo $content; ?>
        </div><!-- content -->
        <div class="row-fluid"><br/></div>
        <div class="row-fluid"><br/></div>
    </div>
    <div class="span3 last">
        <?php
            $this->beginWidget('zii.widgets.CPortlet', array(
                'title' =>  Yii::t('admin', 'Operations'),
            ));
            $this->widget('zii.widgets.CMenu', array(
                'items'         =>  $this->menu,
                'htmlOptions'   =>  array('class' => 'sidebar'),
            ));
            $this->endWidget();
        ?>
    </div>
</div>
<?php $this->endContent(); ?>
