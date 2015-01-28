<?php /* @var $this AdminController */ ?>
<?php $this->beginContent('//layouts/admin/standart'); ?>
    <div class="container-fluid">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="span10 offset1">
                    <a class='brand' href="<?php echo CHtml::encode(Yii::app()->baseUrl) ?>/" target="_blank">
                        <?php echo CHtml::encode(Yii::app()->name) ?>
                    </a>
                    <div class="nav-collapse">
                        <?php
                        if(!Yii::app()->user->isGuest) {
                            $this->widget('application.widgets.MainAdminMenu', array(
                            ));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid main-content">
        <div class='row-fluid'>
            <div class='span10 offset1'>
                <?php /* flash types: error, warning, notice */ ?>
                <?php if(count($flashes = Yii::app()->user->getFlashes())): ?>
                <div class="c-align">
                    <?php foreach ($flashes as $type => $message): ?>
                    <div class="alert alert-<?php echo $type ?>">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php echo $message ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="row-fluid">
                <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'homeLink'          =>  CHtml::link(Yii::t('admin', 'Home'), Yii::app()->createUrl('admin/default/index')),
                    'links'             =>  $this->breadcrumbs,
                )) ?>
                </div>

                <?php echo $content; ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>
