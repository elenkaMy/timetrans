$(document).ready(function() {
    if ($('.fancybox').length > 0) {
        $(".fancybox").fancybox();
    }

    $(document).on('click', '.product-like', function(e) {
        var href = $(this).attr('href'),
            productId = $(this).data('product-id'),
            url = href.replace(new RegExp("__ID__", 'g'), productId),
            $favorites = $('.chochy-products');
        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                if (data.success) {
                    if ($favorites.children().length > 0) {
                        var ids = [];
                        $.each($favorites.children(), function() {
                            ids.push($(this).data('product-id'));
                        });
                        if ($.inArray(productId, ids) === -1) {
                            $favorites.append(data.content);
                        }
                    } else {
                        $favorites.append(data.content);
                    }
                } else {
                    if (data.message) {
                        alert(data.message);
                    }
                }
            }
        });
        e.preventDefault();
    });
    
    $(document).on('click', '.product-dislike', function(e) {
        var href = $(this).attr('href'),
            productId = $(this).data('product-id'),
            url = href.replace(new RegExp("__ID__", 'g'), productId),
            $button = $(this);
        $.ajax({
            type: 'POST',
            url: url,
            success: function(data) {
                if (data.success) {
                    $button.parent().remove();
                } else {
                    if (data.message) {
                        alert(data.message);
                    }
                }
            }
        });
        e.preventDefault();
    });
    
    var $popup = $('.send-modal-form .available');
    $(document).on('click', '.sendButton', function() {
        $.ajax({
            type: 'GET',
            url: $(this).data('url'),
            success: function (data) {
                $popup.html(data.html);
                $popup.show();
            }
        });
        return false;
    });
    
    $(document).on('click', '.mailButton', function(){
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            data: $popup.find('#send-form').serialize(),
            success: function (data) {
                if (data.success) {
                    if (confirm(data.html)) {
                        $popup.hide();
                        $('.call-button').show();
                    }
                } else {
                    $popup.html(data.html);
                }
           }
        });
        return false;
    });
    
    $(document).on('click','.sendButton', function(){
        $('.call-button').hide();
    });

});