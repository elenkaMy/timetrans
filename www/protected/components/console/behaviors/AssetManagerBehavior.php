<?php

class AssetManagerBehavior extends CConsoleCommandBehavior
{
    /**
     * @return CAssetManager
     */
    public function getAssetManager()
    {
        return Yii::app()->assetManager;
    }
}
