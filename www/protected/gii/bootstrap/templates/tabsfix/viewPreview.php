<?php
/**
 * The following variables are available in this template:
 */
/* @var $this BootstrapCode */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
?>

<?php echo "<?php echo '
    $(function () {
        var clicked = false;
        $(\"body\").on(\"click\", \"a, input, button\", function () {
            clicked = true;
        });
        window.onbeforeunload = function () {
            if (clicked) {
                clicked = false;
                return '.CJavaScript::encode(Yii::t('admin', 'Preview displays only on this page. Are you sure?')).';
            }
        };
    });
';?>"
;?>