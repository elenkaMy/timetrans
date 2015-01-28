$(function(){
    var changePosition = function(obj){
        var self = obj;
        var page = self.parent().data('url');
        var url = page.replace(new RegExp("__id__", 'g'), self.closest('tr').children('td:first').text());
        $.ajax({
            type: 'POST',
            url: url,
            data: {'position': self.val()},
            success: function(data){
                if(!data.success){
                    self.siblings('.position-error').html(data.error).show();
                    self.val(data.position);
                } else {
                    $('.position-error').hide();
                }
            },
            beforeSend: function(){
                self.css('width',self.outerWidth()-21);
                self.parent().find('.loader').show();
            },
            complete: function(){
                self.parent().find('.loader').hide();
                self.css('width',self.outerWidth()+21);
            }
        })
    };

    $('body').on('keypress', '.change-position', function(e){
        if(e.which == 13){
            changePosition($(this));
        }
    });

    var val;
    $('body').on('focus', '.change-position', function () {
        val = $(this).val();
    });
    $('body').on('blur', '.change-position', function(){
        if ($(this).val() !== val) {
            changePosition($(this));
        }
    });
})

