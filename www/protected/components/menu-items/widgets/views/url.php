<?php
/* @var $this UrlMenuItemTypeWidget */
/* @var $menuItem MenuItem */
/* @var $menuItemType AbstractMenuItemType */
$isCurrentType = $menuItem->item_type === $menuItemType->shortTypeName;
?>
<?php echo CHtml::textField('item-value', $isCurrentType ? $menuItem->value : '', array(
    'id'    =>  $id = CHtml::ID_PREFIX.(CHtml::$count++),
    'class' =>  'span5'.($isCurrentType && $menuItem->hasErrors('value') ? ' error' : ''),
)); ?>
<?php if ($isCurrentType && $menuItem->hasErrors('value')): ?>
    <?php echo TbHtml::helpBlock($menuItem->getError('value'), array('class' => 'error')) ?>
<?php endif; ?>
<script>
    $(function () {
        $('body').on('change', '#<?php echo $id ?>', function () {
            $('.menu-item-value').val($(this).val());
        });
    });
    $('body').on('changeType', '.menu-item-type', function (event, type) {
        if (type == '<?php echo $menuItemType->shortTypeName ?>') {
            $('.menu-item-value').val($('#<?php echo $id ?>').val());
        }
    });
</script>
