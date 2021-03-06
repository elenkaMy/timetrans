<!-- The file upload form used as target for the file upload widget -->
<?php if ($this -> showForm ) echo CHtml::beginForm($this -> url, 'post', $this -> htmlOptions);?>
<div class="row span12 fileupload-buttonbar">
    <div class="span12">
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span><?php echo Yii::t('admin', $this->multiple ? 'Add files' : 'Choose file') ?></span>
            <?php
            if ($this -> hasModel()) :
                echo CHtml::activeFileField($this -> model, $this -> attribute, $htmlOptions) . "\n";
            else :
                echo CHtml::fileField($name, $this -> value, $this -> attribute, $htmlOptions) . "\n";
            endif;
            ?>
        </span>
        <?php if ($this -> multiple):?>
        <button type="submit" class="btn btn-primary start">
            <i class="icon-upload icon-white"></i>
            <span><?php echo Yii::t('admin', 'Start upload') ?></span>
        </button>
        <button type="reset" class="btn btn-warning cancel">
            <i class="icon-ban-circle icon-white"></i>
            <span><?php echo Yii::t('admin', 'Cancel upload') ?></span>
        </button>
        <button type="button" class="btn btn-danger delete">
            <i class="icon-trash icon-white"></i>
            <span><?php echo Yii::t('admin', 'Delete') ?></span>
        </button>
        <input type="checkbox" class="toggle" />
        <?php endif;?>
    </div>
    <div class="span5">
        <!-- The global progress bar -->
        <div class="progress progress-success progress-striped active fade">
            <div class="bar" style="width:0%;"></div>
        </div>
    </div>
</div>
<!-- The loading indicator is shown during image processing -->
<div class="fileupload-loading"></div>
<br />
<?php if ($this -> showForm) echo CHtml::endForm();?>
