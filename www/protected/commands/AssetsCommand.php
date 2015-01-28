<?php

class AssetsCommand extends AbstractCommand
{
    /**
     * @var array|null if it defined as array than only specified packages will 
     * combined. This option not uses if param package defined in generate action.
     */
    public $defaultProcessPackages = null;

    public function init()
    {
        parent::init();
        $this->defaultAction = 'help';
        Yii::app()->attachBehavior('assetManagerBehavior', array(
            'class'     =>  'application.components.console.behaviors.AssetManagerBehavior',
        ));
    }

    /**
     * This command combined and compress js & css packages
     * @param string $package
     */
    public function actionDump($package = null)
    {
        /* @var $clientScript ExtendedClientScript */
        $clientScript = Yii::app()->clientScript;

        if (!is_null($package)) {
            $packages = array($package);
        } elseif (is_array($this->defaultProcessPackages)) {
            $packages = $this->defaultProcessPackages;
        } else {
            $packages = array_keys($clientScript->packages);
        }

        foreach ($packages as $name) {
            $oldScriptMap = $clientScript->scriptMap;
            $this->printf('Combine & compress %s package', $name);

            $clientScript->registerPackage($name);
            $output = '';
            $clientScript->render($output);

            $clientScript->reset();
            $clientScript->scriptMap = $oldScriptMap;
        }

        $this->printf('Done');
    }
}
