<?php
/* @var $this PreviewWidget */
Yii::app()->clientScript->registerScript('previewButton_'.$this->id, '
    (function () {
        var newWindow = function(url){
            var params = [
                "menubar = no",
                "toolbar = no",
                "location = no",
                "resizable = yes",
                "directories = no",
                "scrollbars = no",
                "status = no",
                "modal = yes"
            ];
            return window.open(url, "preview", params.toString());
        };

        var url = '.CJavaScript::encode($this->url).',
            formId = "#"+'.CJavaScript::encode($this->formId).',
            previewId = "#"+'.CJavaScript::encode($this->id).',
            preview = function(){
                var newWin = newWindow("about:blank"),
                    data = $(formId).serialize();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(data){
                        if(!data.success){
                            newWin.close();
                            alert(data.message);
                            $("body").scrollTop(0);
                        } else {
                            newWin.location.href = data.url;
                        }
                    },
                    error: function (jqXHR, textStatus, error) {
                        if (jqXHR && jqXHR.responseText) {
                            var response = false;
                            try {
                                response = JSON.parse(jqXHR.responseText);
                            } catch (err) {}
                            if (response && response.error) {
                                error = response.error;
                            }
                        }
                        alert(error);
                        newWin.close();
                    }
                });
            };

        var notSubmit = false;

        $("body").on("click", previewId, function() {
            notSubmit = true;
        })

        $("body").on("submit", formId, function() {
            if (notSubmit) {
                preview();
                notSubmit = false;
                return false;
            }
        })
    })();', CClientScript::POS_READY);
?>
<?php echo Chtml::submitButton(Yii::t('admin', 'Preview'), $this->htmlOptions); ?>
