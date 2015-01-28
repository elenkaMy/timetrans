<?php echo "<?php\n"; ?>

class <?php echo $this->moduleClass; ?> extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        /*
        $this->setImport(array(
            '<?php echo $this->moduleID; ?>.components.*',
        ));
        */

<?php
    $maxNameLength = 2/*'*/ + strlen($helperName = $this->moduleID.'Helper');
    $maxNameLength += 4 - ($maxNameLength) % 4;
?>
        // add custom module components
        /*
        $this->setComponents(array(
            <?php echo str_pad("'$helperName'", $maxNameLength); ?>=>  array(
                'class'     =>  '<?php echo $this->moduleID ?>.components.helpers.<?php echo ucfirst($helperName) ?>',
            ),
        ));
        */
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
            return true;
        }

        return false;
    }
}
