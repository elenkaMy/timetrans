<?php
/* @var $this TextMenuItemTypeWidget */
/* @var $menuItemType AbstractMenuItemType */
?>
<script>
    $(function (){
        var type = $('.menu-item-type option:selected').val();
        if(type == 'text') {
            $('.value-label').hide();
        }
        $('body').on('changeType', '.menu-item-type', function (event, type) {
            if (type == '<?php echo $menuItemType->shortTypeName ?>') {
                $('.menu-item-value').val('-');
                $('.value-label').hide();
            } else {
                $('.value-label').show();
            }
        });
    });
</script>