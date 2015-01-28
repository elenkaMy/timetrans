$(function () {
    var changeItemType = function(){
        var type = $('.menu-item-type option:selected').val();
        var wasVisible = $('.rendered-item-type-'+type).is(':visible');

        $('.rendered-item-type').hide();
        $('.rendered-item-type-'+type).show();
        if (!wasVisible) {
            $('.menu-item-type').trigger('changeType', [type]);
        }
    };

    changeItemType();

    $('body').on('change', '.menu-item-type', function(){
        changeItemType();
    });
});
