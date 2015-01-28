<?php
/* @var $this UrlMenuItemTypeWidget */
/* @var $menuItem MenuItem */
/* @var $menuItemType AbstractARMenuItemType */
$isCurrentType = $menuItem->item_type === $menuItemType->shortTypeName;
?>
<?php echo CHtml::dropDownList('item-value', $isCurrentType ? $menuItem->value : '', $menuItemType->dataForList, array(
    'id'    =>  $id = CHtml::ID_PREFIX.(CHtml::$count++),
    'class' =>  'span5'.($isCurrentType && $menuItem->hasErrors('value') ? ' error' : ''),
)); ?>
<?php if ($isCurrentType && $menuItem->hasErrors('value')): ?>
    <?php echo TbHtml::helpBlock($menuItem->getError('value'), array('class' => 'error')) ?>
<?php endif; ?>
<script>
    $(function () {
        var itemNameChanged = false;
        $('body').on('change', '#<?php echo $id ?>', function () {
            $('.menu-item-value').val($(this).find('option:selected').val());
            if (!$('#MenuItem_item_name').val().length || itemNameChanged) {
                $('#MenuItem_item_name').val($(this).find('option:selected').text());
                itemNameChanged = true;
            }
        });
        $('body').on('changeType', '.menu-item-type', function (event, type) {
            if (type == '<?php echo $menuItemType->shortTypeName ?>') {
                $('.menu-item-value').val($('#<?php echo $id ?>').find('option:selected').val());
                if (!$('#MenuItem_item_name').val().length || itemNameChanged) {
                    $('#MenuItem_item_name').val($('#<?php echo $id ?>').find('option:selected').text());
                    itemNameChanged = true;
                }
            } else if (itemNameChanged) {
                $('#MenuItem_item_name').val('');
                itemNameChanged = false;
            }
        });
    });
</script>
