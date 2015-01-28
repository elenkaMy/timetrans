<?php
/**
 * The view file for FileUploader widget.
 */
/* @var $this FileUploader */
/* @var $model CModel */
/* @var $attribute string */
/* @var $hash string */
/* @var $files array */

if (!$this->multiple) {
    Yii::app()->clientScript->registerScript('upload-files-'.$this->htmlOptions['id'], '
        $("#'.$this->htmlOptions['id'].'").bind("fileuploadadd", function (e, data) {
            var $fileWrap = $(this).find(".single-uploaded-file");
            if ($fileWrap.length != 0) {
                $fileWrap.remove();
            }
        });
    ', CClientScript::POS_READY);
}
?>
<table class="table table-striped ">
    <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">
        <?php foreach ($files as $file):?>
        <tr class="template-download<?php echo $this->multiple ? '' : ' single-uploaded-file' ?>">
            <?php if (isset($file['thumbnail_url'])): ?>
            <td class="preview">
                <a href="<?php echo CHtml::encode($file['url']);?>" title="<?php echo CHtml::encode($file['name']);?>" target="_blank" rel="gallery"><img src="<?php echo CHtml::encode($file['thumbnail_url']) ?>" /></a>
            </td>
            <?php endif; ?>
            <td class="name">
                <a href="<?php echo CHtml::encode($file['url']);?>" title="<?php echo CHtml::encode($file['name']);?>" target="_blank"><?php echo CHtml::encode($file['name']);?></a>
            </td>
            <td class="size"><span><?php echo CHtml::encode(Yii::app()->format->size($file['size'])); ?></span></td>
            <td colspan="2"></td>
            <td class="delete">
                <button class="btn btn-danger" data-type="<?php echo CHtml::encode($file['delete_type']) ?>" data-url="<?php echo CHtml::encode($file['delete_url']) ?>">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo Yii::t('admin', 'Delete') ?></span>
                </button>
                <?php if($this->multiple):?>
                <input type="checkbox" name="delete" value="1" />
                <?php else:?>
                <input type="hidden" name="delete" value="1" />
                <?php endif;?>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php echo CHtml::hiddenField(CHtml::resolveName($model, $attribute), $hash) ?>
<?php if ($this->renderErrors && $model->hasErrors($attribute)): ?>
    <div class='help-block error'><?php echo implode(' ', $model->getErrors($attribute)) ?></div>
<?php endif; ?>
